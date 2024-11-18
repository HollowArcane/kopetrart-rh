class FileUploadAdapter
{
    constructor(facadeInput, fileInput, imageContainer)
    {
        if(facadeInput instanceof Node)
        { this.facadeInput = facadeInput; }
        else
        { this.facadeInput = document.getElementById(facadeInput); }
        
        if(fileInput instanceof Node)
        { this.fileInput = fileInput; }
        else
        { this.fileInput = document.getElementById(fileInput); }
        
        if(imageContainer instanceof Node)
        { this.imageContainer = imageContainer; }
        else
        { this.imageContainer = document.getElementById(imageContainer); }
        

        if(!this.fileInput)
        { throw new Error('Invalid fileInput provided'); }

        if(!this.facadeInput)
        { throw new Error('Invalid facadeInput provided'); }

        if(!this.imageContainer)
        { throw new Error('Invalid imageContainer provided'); }

        this.setup();
    }

    setup()
    {
        this.facadeInput.addEventListener('change', this.add.bind(this));
    }

    add(event)
    {
        if(this.facadeInput.files)
        {
            const dataTransfer = new DataTransfer();
            for(let file of this.facadeInput.files)
            {
                if(!file)
                { continue; }
                dataTransfer.items.add(file);
            }
            this.validate(dataTransfer);
        }
    }

    remove(index)
    {
        if(this.fileInput.files)
        {
            const dataTransfer = new DataTransfer();
            for(let i = 0; i < this.fileInput.files.length; i++)
            {
                if(i == index)
                { continue; }

                dataTransfer.items.add(this.fileInput.files[i]);
            }
            this.fileInput.files = dataTransfer.files;
        }
    }

    validate(dataTransfer)
    {
        for(let file of dataTransfer.files)
        {
            const reader = new FileReader();

            const imageWrap = document.createElement('div');
            imageWrap.classList.add('pf__image-upload', 'col-md-6', 'col-lg-4');

            const button = document.createElement('span');
            button.classList.add('position-absolute', 'top-0', 'start-100', 'translate-middle', 'p-2', 'bg-light', 'btn', 'btn-sm', 'btn-light', 'btn-close', 'border', 'border-light', 'rounded-circle');
            button.addEventListener('click', e => {
                const index = Array.prototype.indexOf.call(this.imageContainer.children, imageWrap);
                this.remove(index);

                this.imageContainer.removeChild(imageWrap)
            });

            const image = document.createElement('img');
            imageWrap.append(image, button);

            this.imageContainer.append(imageWrap);
            
            reader.onload = e => image.src = e.target.result;
            reader.readAsDataURL(file);
        }

        for(let file of this.fileInput.files)
        { dataTransfer.items.add(file); }

        this.fileInput.files = dataTransfer.files;
    }
}

class CarouselAdapter
{
    constructor(display, controls)
    {
        this.display = document.getElementById(display);
        this.controls = document.getElementById(controls);

        this.setup();
    }

    setup()
    {
        this.active = 0;

        let images = this.display.querySelectorAll('img');
        for (const image of images)
        { image.classList.remove('active'); }

        images = this.controls.querySelectorAll('img');
        for (let i = 0; i < images.length; i++)
        {
            const index = i;
            images[i].classList.remove('active');
            images[i].addEventListener('click', () => this.set(index));
        }

        this.set(0);
    }

    set(index)
    {
        let displays = this.display.querySelectorAll('img');
        displays[this.active].classList.remove('active');

        let controls = this.controls.querySelectorAll('img');
        controls[this.active].classList.remove('active');

        this.active = index % displays.length;
        displays[this.active].classList.add('active');
        controls[this.active].classList.add('active');
    }

}

class InputMultipleAdapter
{
    constructor(input, btnMore)
    {
        this.input = document.getElementById(input);
        this.btnMore = document.getElementById(btnMore);
        this.index = 1;
        
        this.setup();
    }

    setup()
    { this.btnMore.addEventListener('click', e => this.add()); }

    add()
    {
        const newInput = this.input.cloneNode();
        newInput.setAttribute('id', `${this.input.getAttribute('id')}-${this.index++}`);
        newInput.value = '';
        this.input.parentNode.append(newInput);
    }
}

window.addEventListener('DOMContentLoaded', e => {
    try
    { new FileUploadAdapter('image', 'pseudo-image', 'file-wrap'); }
    catch (error)
    { console.log(error); }
    
    try
    { new CarouselAdapter('display', 'controls'); }
    catch (error)
    { console.log(error); }
    
    try
    { new InputMultipleAdapter('contact', 'more-contact'); }
    catch (error)
    { console.log(error); }
});