<?php
    session_start();
    require_once('vendor/autoload.php');

    use Bramus\Router\Router;
    
    $app = new Router;
    
    $app->set404('app\Controllers\ErrorPage@index');
    

    $app->get('','app\Controllers\SignIn@index');
    $app->get('/register','app\Controllers\SignUp@index');
    $app->get('/login','app\Controllers\SignIn@index');
    $app->post('/signup','app\Controllers\SignUp@signUp');
    $app->post('/signin','app\Controllers\SignIn@signIn');
    $app->get('/dashboard','app\Controllers\Dashboard@index');
    $app->get('/data','app\Controllers\Dashboard@getData');
    $app->get('/chartData','app\Controllers\Dashboard@chartData');
    $app->post('/save-expense','app\Controllers\Dashboard@save');
    $app->get('404','app\Controllers\ErrorPage@index');

    //Middleware
    $app->before('GET','/dashboard',function(){
        if(!isset($_SESSION['user_id'])){
            header('Location:http://your-expenses.com/login');
            exit();
        }
    });

    $app->before('GET','/data',function(){
        if(!isset($_SESSION['user_id'])){
            header('Location:http://your-expenses.com/404');
            exit();
        }
    });

    $app->before('GET','/chartData',function(){
        if(!isset($_SESSION['user_id'])){
            header('Location:http://your-expenses.com/404');
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

   







    