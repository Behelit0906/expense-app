<?php
    namespace app\Controllers;
    use app\Classes\Controller;
    use app\Models\User;


    class SignUp extends Controller{

        public function index(){
            $this->render('login/signUp');
        }
        
        public function signUp(){
            $this->validate([
                'name' => ['required','min:3','max:10'],
                'email' =>['required','email'],
                'password' => ['required','password_confirmation']

            ]);


        

            

        }


    }