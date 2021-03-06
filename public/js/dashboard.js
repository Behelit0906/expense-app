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
    const data = await get_data(domain + '/api/dashboard-data');
    let name = data['user-data']['user_name'];
    let photo = data['user-data']['photo'];

    nameAndPhotoLoader(name,photo);

    balance.textContent = formatter.format(data['user-data']['general_balance']);

    budget.textContent = formatter.format(data['user-data']['residual_budget']);
    if(data['user-data']['residual_budget'] < 0){
        budget.setAttribute('style','color:red');
    }

    totalBudget.textContent = `From ${formatter.format(data['user-data']['budget'])} per month you subtract`;
    biggetsExpense.textContent = formatter.format(data['user-data']['biggets_expense']);
    
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
    
    const response = await fetch(domain + '/api/save-expense',{
            method:"POST",
            body:data
    }).then(e => e.json());

    closeModal();
    this.reset();

    if(response['status-code'] == 201){
        loadData();
        draw_chart();
    }

    showMessages(response['status-code'],response['messages']);

}





