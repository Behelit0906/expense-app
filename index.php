<?php
    session_start();
    require_once('vendor/autoload.php');
    require('app/Config/Config.php');

    use Bramus\Router\Router;
    use app\Controllers\Expense;
     
    $app = new Router;
    
    $app->set404('app\Controllers\ErrorPage@index');
     
    $app->get('','app\Controllers\SignIn@index');
    $app->get('/register','app\Controllers\SignUp@index');
    $app->get('/login','app\Controllers\SignIn@index');
    $app->get('/logout','app\Controllers\SignIn@logOut');
    $app->post('/signup','app\Controllers\SignUp@signUp');
    $app->post('/signin','app\Controllers\SignIn@signIn');
    $app->get('/dashboard','app\Controllers\Dashboard@index');
    $app->get('/profile','app\Controllers\Profile@index');
    $app->get('/expenses','app\Controllers\Expense@index');
    $app->get('/admin-panel','app\Controllers\AdminPanel@index');

    
    //api routes
    $app->get('/api/profile-data','app\Controllers\Profile@userData');
    $app->get('/api/dashboard-data','app\Controllers\Dashboard@getData');
    $app->get('/api/chart-data','app\Controllers\Dashboard@chartData');
    $app->post('/api/save-expense','app\Controllers\Dashboard@save');
    $app->post('/api/update-name', 'app\Controllers\Profile@updateName');
    $app->post('/api/update-photo', 'app\Controllers\Profile@updatePhoto');
    $app->post('/api/update-budget', 'app\Controllers\Profile@updateBudget');
    $app->post('/api/update-password', 'app\Controllers\Profile@updatePassword');
    $app->post('/api/delete-expense', 'app\Controllers\Expense@delete');
    $app->get('/api/expenses-data/{pointer}/{amount}', function($pointer, $amount){
        $expense = new Expense;
        $expense->expensesData($pointer, $amount);
    });
    $app->get('/api/expenses-filter/{category_id}/{pointer}/{amount}', function($category_id, $pointer, $amount){
        $expense = new Expense;
        $expense->filteredExpensesData($category_id, $pointer, $amount);
    });
    $app->get('/api/admin-panel-data','app\Controllers\AdminPanel@userData');
    $app->post('/api/create-category','app\Controllers\Category@save');




    //Middlewares
    $app->before('GET|POST','/api/.*', function(){
        if(!isset($_SESSION['user_id'])){
            header('Location:http://your-expenses.com/404');
            exit();
        }
    });
   
    $app->before('GET','/dashboard',function(){
        if(!isset($_SESSION['user_id'])){
            header('Location:http://your-expenses.com/login');
            exit();
        }
    });

    $app->before('GET','/admin-panel',function(){
        if(!isset($_SESSION['user_id'])){
            header('Location:http://your-expenses.com/login');
            exit();
        }
    });

    $app->before('GET','/expenses',function(){
        if(!isset($_SESSION['user_id'])){
            header('Location:http://your-expenses.com/login');
            exit();
        }
    });

    $app->before('GET','/profile',function(){
        if(!isset($_SESSION['user_id'])){
            header('Location:http://your-expenses.com/login');
            exit();
        }
    });

    $app->run();  