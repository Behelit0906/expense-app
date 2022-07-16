
var nameFields;
var nameInput;
var headerPhoto;
var navbarPhoto;
var budgetInput;
var formPhoto;

document.addEventListener('DOMContentLoaded', function(){

    nameInput = document.getElementById('name');
    budgetInput = document.getElementById('budget');
    document.getElementById('nameForm').addEventListener('submit', sendForm);
    document.getElementById('photoForm').addEventListener('submit', sendForm);
    document.getElementById('passwordForm').addEventListener('submit', sendForm);
    document.getElementById('budgetForm').addEventListener('submit', sendForm);
    
    loadData();
    dropDownMenu();
});



async function loadData(){
    const data = await get_data(domain + '/api/profile-data');

    if(data['status-code'] == 200){

        let name = data['user-data']['user_name'];
        let photo = data['user-data']['photo'];
        let budget = data['user-data']['budget'];

        nameAndPhotoLoader(name, photo);


        nameInput.value = name;
        budgetInput.value = budget;

    }
}



async function sendForm(event){
    event.preventDefault();
    let formName = this.getAttribute('id');
    
    let data = new FormData(this);
    let url = domain;

    switch(true){
        case formName == 'nameForm':
            url += '/api/update-name';
            break;

        case formName == 'photoForm':
            url += '/api/update-photo';
            break;
        
        case formName == 'passwordForm':
            url += '/api/update-password';
            break;

        case formName == 'budgetForm':
            url += '/api/update-budget';
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

    showMessages(response['status-code'], response['messages']);
    loadData();
   
}

