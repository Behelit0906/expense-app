<?php
    session_start();
    require_once('vendor/autoload.php');

    use Bramus\Router\Router;
    
    $app = new Router;
    

    

    $app->get('','app\Controllers\SignIn@index');
    $app->get('/register','app\Controllers\SignUp@index');
    $app->get('/login','app\Controllers\SignIn@index');
    $app->post('/signup','app\Controllers\SignUp@signUp');
    $app->post('/signin','app\Controllers\SignIn@signIn');
    $app->get('/dashboard','app\Controllers\Dashboard@index');
    $app->get('/data','app\Controllers\Dashboard@getData');
    $app->post('/save-expense','app\Controllers\Dashboard@save');

    //Middleware
    $app->before('GET','/dashboard',function(){
        if(!isset($_SESSION['user_id'])){
            header('Location:http://your-expenses.com/login');
            exit();
        }
    });

    $app->before('GET','/data',function(){
        if(!isset($_SESSION['user_id'])){
            header('Location:http://your-expenses.com/login');
            exit();
        }
    });

    $app->before('POST','/save-expense',function(){
        if(!isset($_SESSION['user_id'])){
            header('Location:http://your-expenses.com/login');
            exit();
        }
    });
    



    

    $app->run();

   







    