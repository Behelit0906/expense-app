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
                'name' => ['required','min:3','max:20'],
                'email' =>['required','email'],
                'password' => ['required','password_confirmation']
            ]);

            $name = $_POST['name'];
            $email = $_POST['email'];
            $pass = password_hash($_POST['password'],PASSWORD_BCRYPT);

            $user = new User;
            if($user->findByEmail($email)){
                $_SESSION['validateErrors'] = ['Email is already registered'];
                $this->redirect($_SERVER['HTTP_REFERER']);
            }

            $user->name = $name;
            $user->email = $email;
            $user->password = $pass;
            $user->store();

            $_SESSION['successMessage'] = 'Successful registration';
            $this->redirect('http://your-expenses.com/login');      
        }


    }