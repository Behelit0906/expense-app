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
    category_selecter = document.getElementById('category-selecter');
    footer = document.getElementById('footer');
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

    const data = await get_data(domain + `/api/expenses-data/${page}/${amount}/`);
    let name = data['user-data']['user_name'];
    let photo = data['user-data']['photo'];
    nameAndPhotoLoader(name, photo);
    categories = data['categories'];
    selecterOptions();
    dropDownMenu();
    loadData();

});



async function loadData(){

    const category_id = category_selecter.value;

    let url = '';
    if(category_id == 'null'){
        url = `/api/expenses-data/${page}/${amount}/`;
    }
    
    if(category_id != 'null'){
        url = `/api/expenses-filter/${category_id}/${page}/${amount}/`;      
    } 
    
    const data = await get_data(domain + url);

    page = 0;
    table_body.innerHTML = '';
    buildRows(data);
    totalPages = Math.ceil(data['total']/amount);
    setPageNumber();
}

function buildRows(data){
    
    table_body.innerHTML = '';
    data['expenses'].forEach(element => {
        const row = document.createElement('tr');

        const name = document.createElement('td');
        name.textContent = element['name'];

        const category = document.createElement('td');

        if(element['category_id'] != null){
            data['categories'].forEach(e => {
                if (element['category_id'] == e['id']){
                    category.textContent = e['name'];
                    
                }
            });
        }
        else{
            category.textContent = 'No category';
        }


        const date = document.createElement('td');
        date.textContent = element['date'];

        const amount = document.createElement('td');
        amount.textContent = formatter.format(element['amount']);

        const btn_delete = document.createElement('td');
        const form = document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('class','delete-form');
        form.addEventListener('submit',sendForm);
        const input = document.createElement('input');
        input.setAttribute('type','hidden');
        input.setAttribute('name','id');
        input.value = element['id'];
        const button = document.createElement('button');
        button.textContent = 'Delete';
        button.setAttribute('type','submit');
        button.setAttribute('class','button background-blue font-white');

        form.appendChild(input);
        form.appendChild(button);

        btn_delete.appendChild(form);


        row.appendChild(name);
        row.appendChild(category);
        row.appendChild(date);
        row.appendChild(amount);
        row.appendChild(btn_delete);

        table_body.appendChild(row);
    });

}

function selecterOptions(){

    const void_option = document.createElement('option');
    void_option.setAttribute('value', 'null');
    void_option.textContent = '---';
    category_selecter.appendChild(void_option);


    categories.forEach(element => {
        const option = document.createElement('option');
        option.setAttribute('value', element['id']);
        option.textContent = element['name'];
        category_selecter.appendChild(option);

    });

    category_selecter.addEventListener('change', loadData);

}


async function sendForm(event){
    event.preventDefault();
   
    let data = new FormData(this);
    let url = domain + '/api/delete-expense';
    
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

    let category_id = document.getElementById('category-selecter').value;
    let url = domain;
    let pointer = page * amount;

    if(category_id != 'null'){
        url += `/api/expenses-filter/${category_id}/${pointer}/${amount}/`;
    }
    else{
        url += `/api/expenses-data/${pointer}/${amount}/`
    }

    const data = await get_data(url);
    buildRows(data);

}