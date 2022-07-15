document.addEventListener("DOMContentLoaded", async function() {

    document.getElementById('categoryForm').addEventListener('submit', sendForm);
    const data = await get_data('http://your-expenses.com/api/admin-panel-data');
    const name = data['user-data']['user_name'];
    const photo = data['user-data']['photo'];

    nameAndPhotoLoader(name, photo);
    dropDownMenu();

});

async function sendForm(event){
    event.preventDefault();
    let data = new FormData(this);
    let url = 'http://your-expenses.com/api/create-category';

    const response = await fetch(url,{
        method:"POST",
        body:data
    }).then(e => e.json());

    closeModal();
    this.reset();

    showMessages(response['status-code'], response['messages']);
    if(response['status-code'] == 201){
        const l = document.getElementById('categoriesCreated');
        l.textContent = parseInt(l.textContent) + 1;
    }
}




