<?php
    namespace app\Controllers;

    use app\Classes\Controller;
    use app\Models\User;


    class SignIn extends Controller{
    
        public function index(){
            $this->render('login/signIn',['currentPage' => 'signup']);   
        }


        public function signIn(){

            $this->validate([
                'email' =>['required','email'],
                'password' => ['required','min:8','max:16']
            ]);

            $email = $_POST['email'];
            $pass = $_POST['password'];

            $user = new User;

            if(!$user->findByEmail($email)){
                $_SESSION['errors'] = ['Incorrect email address'];
                $this->redirect($_SERVER['HTTP_REFERER']);
            }

            if(!password_verify($pass, $user->password)){
                $_SESSION['errors'] = ['Incorrect password'];
                $this->redirect($_SERVER['HTTP_REFERER']);
            }


        }

    }