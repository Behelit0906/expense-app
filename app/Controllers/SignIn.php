<?php
    namespace app\Controllers;

    use app\Classes\Controller;
    use app\Models\User;


    class SignIn extends Controller{

        public function __construct()
        {
            parent::__construct();

            if(isset($_SESSION['user_id'])){
                $role = $this->checkRole($_SESSION['user_id']);
                if($role == 'user'){
                    $this->redirect('http://your-expenses.com/dashboard');
                }
                elseif($role == 'admin'){
                    $this->redirect('http://your-expenses.com/admin-panel');
                }
            }

        }
    
        public function index(){
            $this->render('login/signIn',['currentPage' => 'login']);   
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

            $_SESSION['user_id'] = $user->id;
            if($user->rol == 'user'){
                $this->redirect('http://your-expenses.com/dashboard');
                exit();
            }
            elseif($user->rol == 'admin'){
                $this->redirect('http://your-expenses.com/admin-panel');
                exit();
            }


        }

    }