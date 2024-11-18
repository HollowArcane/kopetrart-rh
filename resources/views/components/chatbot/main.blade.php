<style>
    ul {
        list-style: disc;
    }

    li {
        margin-bottom: 1rem;
    }
</style>

<div class="card border rh__chatbox">
    <div class="card-head" style="background-color: #dd3675; border-radius: .5rem .5rem 0 0">
        <p class="pt-3 px-4 text-white fw-bold"> Demandez Vos Questions </p>
    </div>

    <div id="chatbox" class="card-body rh__chatbox-body">
        @foreach ($messages as $message)
            <x-chatbot.message :$message></x-chatbot.message>
        @endforeach
    </div>

    <div class="card-footer">
        <div class="input-group">
            <input class="form-control" type="text" id="chatbox-prompt">
            <x-button.secondary id="btn-chat"> <i class="fa fa-paper-plane"></i> </x-button.secondary>
        </div>
    </div>
</div>

<script type="importmap">
{
    "imports": {
        "@google/generative-ai": "https://esm.run/@google/generative-ai"
    }
}
</script>

<script type="module">
import { GoogleGenerativeAI } from "@google/generative-ai";

const API_KEY = "AIzaSyBaAAlsawiGZEFYsCFjg46cvKt8Dv8IrFg";

const genAI = new GoogleGenerativeAI(API_KEY);
const model = genAI.getGenerativeModel({ model: "gemini-1.5-pro" });

const btn = document.querySelector('#btn-chat');
const input = document.querySelector('#chatbox-prompt');
const chatbox = document.querySelector('#chatbox');

const loaderNode = newNode('div', { class: `row pl-5 mb-3  pop-left` }, [
    newNode('div', { class: `message-item rounded bg-lightgrey d-flex justify-content-center align-items-center p-3` }, [
        newNode('div', { class: 'loader' }, [])
    ])
]);

const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

const user = <?= json_encode($user) ?>;

async function chat(model)
{
    const prompt = input.value;
    input.value = '';

    // APPEND NEW POMPT
    await addPrompt(prompt);
    displayMessage('right', 'bg-primary text-white', prompt)
    displayLoader();

    model.generateContent(prompt).then(result => {
        const response = result.response.text();
        // APPEND NEW RESPONSE
        addResponse(response);
    }).catch(e => {
        loaderNode.remove();
        displayError('left', e)
    });
}

async function addPrompt(message)
{
    return await fetch('/front/message', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            id_login_sender: user.id,
            id_login_target: null,
            content: message,
            created_at: getTimeYMDHMS()
        })
    }).catch(e => displayError('right', e));
}

function addResponse(message)
{
    const messageHTML = textToHTML(message);

    fetch('/front/message', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            id_login_sender: null,
            id_login_target: user.id,
            content: message,
            created_at: getTimeYMDHMS()
        })
    }).then(data => displayMessage('left', 'bg-lightgrey', messageHTML))
    .catch(e => displayError('left', e));
}

function textToHTML(text)
{
    const lines = text.split("\n").map(l => l.trim());
    let html = '';
    let buffer = '';
    let superWrap = '';

    for(let line of lines)
    {
        if(line.length === 0 && superWrap.length > 0)
        { continue; }

        let index = 0;
        let next = 0;
        let open = false;

        let stack = '';
        let wrap = 'p';

        if(line.startsWith('. **', 1) || line.startsWith('. **', 2))
        {
            wrap = 'li';
            if(superWrap != 'ol')
            {
                html += (superWrap != '' ? `<${superWrap}>`: '') + buffer + (superWrap != '' ? `</${superWrap}>`: '');
                superWrap = 'ol';
                buffer = '';
            }

            line = line.substring(3);
        }
        else if(line.startsWith('* **'))
        {
            wrap = 'li';
            if(superWrap != 'ul')
            {
                html += (superWrap != '' ? `<${superWrap}>`: '') + buffer + (superWrap != '' ? `</${superWrap}>`: '');
                superWrap = 'ul';
                buffer = '';
            }
            line = line.substring(2);
        }
        else
        {
            if(line.startsWith('#'))
            { wrap = 'h1'; }
            else if(line.startsWith('**'))
            { wrap = 'h2'; }

            if(superWrap != '')
            {
                html += (superWrap != '' ? `<${superWrap}>`: '') + buffer + (superWrap != '' ? `</${superWrap}>`: '');
                superWrap = '';
                buffer = '';
            }
        }

        while((next = line.indexOf('**', index)) >= 0)
        {
            if(!open)
            { stack += line.substring(index, next) + '<strong class="fs-5">'; }
            else
            { stack += line.substring(index, next) + '</strong> <br />'; }

            index = next + 2;
            open = !open;
        }

        buffer += `<${wrap}>` + stack + line.substring(index) + ` </${wrap}>`;
    }
    html += (superWrap != '' ? `<${superWrap}>`: '') + buffer + (superWrap != '' ? `</${superWrap}>`: '');
    return html;
}

function displayLoader()
{
    chatbox.append(loaderNode);
    chatbox.scrollTop = chatbox.scrollHeight;
}

function displayMessage(direction, styling, message)
{
    loaderNode.remove();

    const messageBody = newNode('div', { class: 'message-item-body p-3' }, []);
    messageBody.innerHTML = message;

    chatbox.append(newNode('div', { class: `row pl-5 mb-3  pop-${ direction }` }, [
        newNode('div', { class: `message-item rounded ${ styling }` }, [
            newNode('div', { class: 'message-item-head' }, [
                // newNode('span', { class: 'message-item-user' }, [ textNode(user.username) ]),
                newNode('small', { class: 'message-item-time' }, [ textNode(getTimeHS()) ]),
            ]),
            messageBody,
        ])
    ]));
    chatbox.scrollTop = chatbox.scrollHeight;
}

function displayError(direction, e)
{
    displayMessage(direction, 'bg-danger text-white', e.message);
}

function getTimeHS()
{
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    return `${hours}:${minutes}`;
}

function getTimeYMDHMS()
{
    const now = new Date();
    const year = now.getFullYear();
    const month = (now.getMonth() + 1).toString().padStart(2, '0');
    const day = now.getDate().toString().padStart(2, '0');

    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

chatbox.scrollTop = chatbox.scrollHeight;
chatbox.style.scrollBehavior = 'smooth';
const items = chatbox.querySelectorAll('.message-item');
for(const item of items)
{
    if(item.classList.contains('bg-lightgrey'))
    {
        const itemBody = item.querySelector('.message-item-body');
        const text = itemBody.textContent;
        itemBody.innerHTML = textToHTML(text);
    }
}


btn.onclick = e => chat(model);
input.onkeypress = e => {
    if(e.keyCode == 13 && input.value.trim() != '')
    { chat(model); }
}
</script>
