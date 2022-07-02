// https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js

var elemets;
var balance;
var totalBudget;
var totalBudget;
var biggetsExpense;
var options;
var formulario;

document.addEventListener("DOMContentLoaded", async function() {
    
    elemets = document.getElementsByClassName('username');
    balance = document.getElementById('balance');
    budget = document.getElementById('budget');
    totalBudget = document.getElementById('total-budget');
    biggetsExpense = document.getElementById('biggetsExpense');
    options = document.getElementById('categoryId');
    formulario = document.getElementById("expenseForm");
    formulario.addEventListener('submit',registerExpense)

    loadData();  
    
});

async function loadData(){
    const data = await get_data();

    for(let i = 0; i < elemets.length; i++){
        elemets[i].textContent = data['user_name'];
    }

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
    for(i = 0; i < data['categories'].length; i++){
        const option = document.createElement('option');
        option.setAttribute('value',data['categories'][i]['id']);
        option.textContent = data['categories'][i]['name'];
        options.appendChild(option);
    }
}

async function get_data() {  
    const response =  await fetch('http://your-expenses.com/data');
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
    
    const response = await fetch('http://your-expenses.com/save-expense',{
            method:"POST",
            body:data
    }).then(e => e.json());

    loadData()
    console.log(response);

}

