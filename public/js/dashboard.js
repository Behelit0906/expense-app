// https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js

var elemets;
var balance;
var totalBudget;
var totalBudget;
var biggetsExpense;
var options;
var formulario;
var photo;

document.addEventListener("DOMContentLoaded", function() {
    
    photo = document.getElementById('profile-photo');
    elemets = document.getElementsByClassName('username');
    balance = document.getElementById('balance');
    budget = document.getElementById('budget');
    totalBudget = document.getElementById('total-budget');
    biggetsExpense = document.getElementById('biggetsExpense');
    options = document.getElementById('categoryId');
    formulario = document.getElementById("expenseForm");
    formulario.addEventListener('submit',registerExpense);

    loadData();
    dropDownMenu();
    
});

async function loadData(){
    const data = await get_data();

    for(let i = 0; i < elemets.length; i++){
        elemets[i].textContent = data['user_name'];
    }

    photo.setAttribute('src','public/profile-pictures (symlink)/'+data['photo']);

    balance.textContent = formatter.format(data['general_balance']);

    budget.textContent = formatter.format(data['residual_budget']);
    if(data['residual_budget'] < 0){
        budget.setAttribute('style','color:red');
    }

    totalBudget.textContent = `From ${formatter.format(data['budget'])} per month you subtract`;
    biggetsExpense.textContent = formatter.format(data['biggets_expense']);
    
    buildCategoryCards(data['category_transactions']);
    loadRecentExpenses(data['recent_expenses']);


    //Agrego las categorias al input select
    options.innerHTML = '';
    for(i = 0; i < data['categories'].length; i++){
        const option = document.createElement('option');
        option.setAttribute('value',data['categories'][i]['id']);
        option.textContent = data['categories'][i]['name'];
        options.appendChild(option);
    }
}

async function get_data() {  
    const response =  await fetch('http://your-expenses.com/api/dashboard-data');
    return await response.json();
}

const formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
});

function buildCategoryCards(data){
    const container = document.getElementById('category-card-container');
    container.innerHTML = '';

    for(i = 0; i < data.length; i++){
        const div = document.createElement("div");
        div.setAttribute('class','category-card');
        div.setAttribute('style','background-color:'+data[i]['color']);

        const span = document.createElement('span');
        span.setAttribute('class','small-font');
        span.textContent = data[i]['name'];

        const childDiv = document.createElement('div');
        childDiv.setAttribute('class','category-amount');
        childDiv.textContent = formatter.format(data[i]['amount']);

        const p = document.createElement('p');
        p.setAttribute('class','transaction-number');
        p.textContent = data[i]['transactions'] + ' transaction';

        div.appendChild(span);
        div.appendChild(childDiv);
        div.appendChild(p);

        container.appendChild(div);

    }

}

function loadRecentExpenses(data){
    const container = document.getElementById('recent-expenses-container');
    container.innerHTML = '';
    for(i = 0; i < data.length; i++){
        const div = document.createElement("div");
        div.setAttribute('class','margin-bottom-20');

        const nameDiv = document.createElement('div');
        nameDiv.setAttribute('class','display-inline-block width-150');

        const span = document.createElement('span');
        span.setAttribute('class','small-font opaque-font');
        span.textContent = data[i]['date'];

        const p = document.createElement('p');
        p.setAttribute('class','margin-0');
        p.textContent = data[i]['name'];

        nameDiv.appendChild(span);
        nameDiv.appendChild(p);

        const amountDiv = document.createElement('div');
        amountDiv.setAttribute('class','display-inline-block font-weight-bold');
        amountDiv.textContent = formatter.format(data[i]['amount']);

        div.appendChild(nameDiv);
        div.appendChild(amountDiv)

        container.appendChild(div);
        
        // <div class="margin-bottom-20">
        //     <div class="display-inline-block margin-right-35">
        //         <span class="small-font opaque-font">2022-06-29</span>
        //         <p class="margin-0">Prueba</p>
        //     </div>
        //     <div class="display-inline-block font-weight-bold">
        //         $300.00
        //     </div>
        // </div>


    }

}


async function registerExpense(evento){
    evento.preventDefault();
    
    let data = new FormData(this);
    
    const response = await fetch('http://your-expenses.com/api/save-expense',{
            method:"POST",
            body:data
    }).then(e => e.json());

    const container = document.getElementById('modal-background');
    const div = document.getElementById('modalMessages');
    div.innerHTML = '';
    

    if(response.hasOwnProperty('errors')){
        div.setAttribute('class','errors top-position');

        for(i = 0; i < response['errors'].length; i++){
            const p = document.createElement("p");
            p.setAttribute('class','margin-3');
            p.textContent = response['errors'][i];
            div.appendChild(p);
        }
            
    }
    else if(response.hasOwnProperty('success')){
        div.setAttribute('class','success top-position');
        const p = document.createElement("p");
        p.setAttribute('class','margin-3');
        p.textContent = response['success'];
        div.appendChild(p);
        loadData();
    }

    container.insertAdjacentElement('afterbegin',div);

    
    

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

