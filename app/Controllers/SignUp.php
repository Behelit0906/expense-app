<?php
    namespace app\Controllers;
    use app\Classes\Controller;
    use app\Models\User;


    class SignUp extends Controller{

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
            $this->render('login/signUp',['currentPage' => 'signup']);
        }
        
        public function signUp(){
            $this->validate([
                'name' => ['required','min:3','max:20'],
                'email' =>['required','email'],
                'password' => ['required','min:8','max:16','password_confirmation']
            ]);


            $name = $_POST['name'];
            $email = $_POST['email'];
            $pass = password_hash($_POST['password'],PASSWORD_BCRYPT);

            $user = new User;
            if($user->findByEmail($email)){
                $_SESSION['errors'] = ['Email is already registered'];
                $this->redirect($_SERVER['HTTP_REFERER']);
                exit(); 
            }

            $user->name = $name;
            $user->email = $email;
            $user->password = $pass;
            $user->store();

            $_SESSION['success'] = 'Successful registration';
            $this->redirect('http://your-expenses.com/login');
            exit();      
        }

    }