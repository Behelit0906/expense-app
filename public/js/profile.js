
var nameFields;
var nameInput;
var headerPhoto;
var navbarPhoto;
var budgetInput;
var formPhoto;

document.addEventListener('DOMContentLoaded', function(){

    nameFields = document.getElementsByClassName('username');
    nameInput = document.getElementById('name');
    headerPhoto = document.getElementById('profile-header-photo');
    navbarPhoto = document.getElementById('profile-photo');
    budgetInput = document.getElementById('budget');
    formPhoto = document.getElementById('form-user-photo');
    document.getElementById('nameForm').addEventListener('submit', sendForm);
    document.getElementById('photoForm').addEventListener('submit', sendForm);
    document.getElementById('passwordForm').addEventListener('submit', sendForm);
    document.getElementById('budgetForm').addEventListener('submit', sendForm);
    
    
    loadData();
    dropDownMenu();
});

async function get_data() {  
    const response =  await fetch('http://your-expenses.com/api/profile-data');
    return await response.json();
}

async function loadData(){
    const data = await get_data();
    if(data['status-code'] == 200){

        let name = data['user-data']['name'];
        let photo = data['user-data']['photo'];
        let budget = data['user-data']['budget'];

        //loading name in navbar and profile's header
        for(let i = 0; i < nameFields.length; i++){
            nameFields[i].textContent = name;
        }

        nameInput.value = name;
        budgetInput.value = budget;

        navbarPhoto.setAttribute('src','public/profile-pictures (symlink)/' + photo);
        headerPhoto.setAttribute('src','public/profile-pictures (symlink)/' + photo);
        formPhoto.setAttribute('src','public/profile-pictures (symlink)/' + photo);
    }
}

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

async function sendForm(event){
    event.preventDefault();
    let formName = this.getAttribute('id');
    
    let data = new FormData(this);
    let url = '';

    switch(true){
        case formName == 'nameForm':
            url = 'http://your-expenses.com/api/update-name';
            break;

        case formName == 'photoForm':
            url = 'http://your-expenses.com/api/update-photo';
            break;
        
        case formName == 'passwordForm':
            url = 'http://your-expenses.com/api/update-password';
            break;

        case formName == 'budgetForm':
            url = 'http://your-expenses.com/api/update-budget';
            break;
    }

    if(formName == 'passwordForm'){
        document.getElementById('password').value = '';
        document.getElementById('password_confirmation').value = '';
    }

    const response = await fetch(url,{
            method:"POST",
            body:data
    }).then(e => e.json());

    const messagesDiv = document.getElementById('messages');
    messagesDiv.innerHTML = '';
    
    console.log(response);
    if(response['status-code'] == 201){
        messagesDiv.setAttribute('class','success position-sticky width-100');
        const p = document.createElement("p");
        p.setAttribute('class','margin-3');
        p.textContent = response['messages'];
        messagesDiv.appendChild(p);
    }
    else if(response['status-code'] == 400){
        messagesDiv.setAttribute('class','errors position-sticky width-100');

        for(i = 0; i < response['messages'].length; i++){
            const p = document.createElement("p");
            p.setAttribute('class','margin-3');
            p.textContent = response['messages'][i];
            messagesDiv.appendChild(p);
        }
    } 
    
    loadData();

    setTimeout(function(){
        messagesDiv.removeAttribute('class');
        messagesDiv.innerHTML = '';
    },3000);
   
}

