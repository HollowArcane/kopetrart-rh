function textNode(text)
{ return document.createTextNode(text); }

function newNode(tagname, attributes = {}, children)
{
    const node = document.createElement(tagname);
    
    for(let attribute in attributes)
    {
        const attrs = attribute.split(",");
        for(let attr of attrs)
        { node.setAttribute(attr, attributes[attribute]); }
    }
    
    for(let child of children)
    { node.append(child); }
    
    return node;
}

function newTR(object, keys = null, attributes = {})
{
    const tds = [];
    
    if(keys == null)
    {
        for(let key in object)
        { tds.push(newNode("td", {}, [ textNode(object[key]) ])); }
    }
    else
    {
        for(let key of keys)
        { tds.push(newNode("td", {}, [ textNode(object[key]) ])) }
    }
    
    return newNode("tr", attributes, tds);
}

function newTable(data, keys = null, attributes = {})
{
    const head = [];
    const rows = [];
    
    if(Array.isArray(object))
    {
        if(data.length == 0)
        { return newNode("table", {}, []); }
        
        head = Object.keys(data[0]).map(key => newNode("th", {}, [textNode(key)]));
        rows = data.map(line => newTR(line, keys, {}));
    }
    else
    {
        for(let key in data)
        {
            const ref = { "-": key, ...data[key] };

            if(head.length == 0)
            { head = Object.keys(ref).map(k => newNode("td", {}, [textNode(k)])); }
            rows.push(newTR(ref, keys, {}));
        }
    }
    
    return newNode("table", attributes, [
        newNode("thead", {}, [newNode("tr", {}, head)]),
        newNode("tbody", {}, rows)
    ]);
}

function getId(id)
{ return document.getElementById(id); }

function getQuery(query)
{ return document.querySelector(query); }

function getAllQuery(query)
{ return document.querySelectorAll(query); }