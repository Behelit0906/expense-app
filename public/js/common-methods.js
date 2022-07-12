
async function get_data(url) {  
    const response =  await fetch(url);
    return await response.json();
}

//To format currency 
const formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
});

function dropDownMenu(){
    const btn = document.getElementById('dropMenu-btn');
    
    const dropMenu = document.getElementById('dropdown-menu');

    btn.onclick = function(){
        let display = dropMenu.getAttribute('style');
        
        if(display == 'display: none;'){
            dropMenu.setAttribute('style','display: block;');
        }
        else{
            dropMenu.setAttribute('style','display: none;');
        }
    }

    window.onclick = function(event){
        let display = dropMenu.getAttribute('style');
        if(display == 'display: block;' && event.target != btn){
            dropMenu.setAttribute('style','display: none;');
        } 
    }
}

function nameAndPhotoLoader(username, photo){
    let nameFields = document.getElementsByClassName('username');
    let photoFields = document.getElementsByClassName('picture');

    for(let i = 0; i < nameFields.length; i++){
        nameFields[i].textContent = username;
    }
    

    for(let i = 0; i < photoFields.length; i++){
        photoFields[i].setAttribute('src','public/profile-pictures (symlink)/'+ photo);
    }
}

function showMessages(statusCode, messages){

    const messagesDiv = document.getElementById('messages');
    messagesDiv.innerHTML = '';

    if(statusCode == 201){
        messagesDiv.setAttribute('class','success position-sticky width-100');
        const p = document.createElement("p");
        p.setAttribute('class','margin-3');
        p.textContent = messages;
        messagesDiv.appendChild(p);
    }

    else if(statusCode == 400){
        messagesDiv.setAttribute('class','errors position-sticky width-100');
        for(i = 0; i < messages.length; i++){
            const p = document.createElement("p");
            p.setAttribute('class','margin-3');
            p.textContent = messages[i];
            messagesDiv.appendChild(p);
        }
    } 

    setTimeout(function(){
        messagesDiv.removeAttribute('class');
        messagesDiv.innerHTML = '';
    },3000);
    
}