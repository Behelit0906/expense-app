<?php
    session_start();
    require_once('vendor/autoload.php');

    
    use Bramus\Router\Router;
    

    $app = new Router;
    
    
    

    //Middleware
   /*  $app->before('GET','/register',function(){
        echo 'perrro con perro <br>';
    });
 */

    
    $app->get('/register','app\Controllers\SignUp@index');
    $app->post('/signup','app\Controllers\SignUp@signUp');

    $app->get('/login','app\Controllers\SignIn@index');




    $app->run();







    