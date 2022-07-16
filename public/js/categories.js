var page;
const amount = 5;
var table_body;
var category_selecter;
var categories;
var footer;
var totalPages;
var pageSpan;

document.addEventListener('DOMContentLoaded', async function(){
    page = 0;
    table_body = document.getElementById('table-body');
    const previous = document.getElementById('previous-page');
    const next = document.getElementById('next-page');
    pageSpan = document.getElementById('page');

    previous.addEventListener('click', function(){
        if(page > 0){
            page --;
            change_page();
            setPageNumber();
        }
    });

    next.addEventListener('click', function(){
        if(page < totalPages - 1){
            page ++;
            change_page();
            setPageNumber();
        }   
    })

    const data = await get_data(`http://your-expenses.com/api/categories-data/${page}/${amount}/`);
    let name = data['user-data']['user_name'];
    let photo = data['user-data']['photo'];
    nameAndPhotoLoader(name, photo);
    dropDownMenu();
    loadData();

});



async function loadData(){

    let url = `http://your-expenses.com/api/categories-data/${page}/${amount}/`
    const data = await get_data(url);
    page = 0;
    table_body.innerHTML = '';
    buildRows(data);
    totalPages = Math.ceil(data['total']/amount);
    setPageNumber();
}

function buildRows(data){
    
    table_body.innerHTML = '';
    data['categories'].forEach(element => {
        const row = document.createElement('tr');

        const name = document.createElement('td');
        name.textContent = element['name'];

        const color = document.createElement('td');
        const div = document.createElement('div');
        div.setAttribute('class', 'category-color-container');
        div.setAttribute('style', `background-color:${element['color']};`);
        color.appendChild(div);

        const actions = document.createElement('td');
        const form = document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('class','delete-form display-inline-block');
        form.addEventListener('submit',sendForm);
        const input = document.createElement('input');
        input.setAttribute('type','hidden');
        input.setAttribute('name','id');
        input.value = element['id'];
        const button = document.createElement('button');
        button.textContent = 'Delete';
        button.setAttribute('type','submit');
        button.setAttribute('class','button background-blue font-white');

        const editForm = document.createElement('form');
        editForm.setAttribute('action',`http://your-expenses.com/categories/edit`);
        editForm.setAttribute('method','get');
        editForm.setAttribute('class','delete-form display-inline-block margin-right-10');
        const editInput = document.createElement('input');
        editInput.setAttribute('type','hidden');
        editInput.setAttribute('name','id');
        editInput.value = element['id'];
        const editButton = document.createElement('button');
        editButton.textContent = 'Edit';
        editButton.setAttribute('type','submit');
        editButton.setAttribute('class','button background-blue font-white');

        form.appendChild(input);
        form.appendChild(button);
        editForm.appendChild(editInput);
        editForm.appendChild(editButton);

        actions.appendChild(editForm);
        actions.appendChild(form);
    
        row.appendChild(name);
        row.appendChild(color);
        row.appendChild(actions);

        table_body.appendChild(row);
    });

}


async function sendForm(event){
    event.preventDefault();
   
    let data = new FormData(this);

    let url = 'http://your-expenses.com/api/delete-category';
    
    const response = await fetch(url,{
            method:"POST",
            body:data
    }).then(e => e.json());

    if(response['status-code'] == 201){
        loadData();
    }
    
    showMessages(response['status-code'], response['messages']);
     
}


function setPageNumber(){
    pageSpan.textContent = page + 1;
}


async function change_page(){

    let pointer = page * amount;
    let url = `http://your-expenses.com/api/categories-data/${pointer}/${amount}/`;
    
    const data = await get_data(url);
    buildRows(data);

}