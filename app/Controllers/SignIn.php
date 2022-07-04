<?php
    namespace app\Controllers;

    use app\Classes\Controller;
    use app\Models\User;
    use app\Classes\Validator;


    class SignIn extends Controller{

        public function __construct()
        {
            parent::__construct();

            if(isset($_SESSION['user_id'])){
                $role = $this->checkRole($_SESSION['user_id']);
                if($_SERVER['REQUEST_URI'] != '/logout'){
                    if($role == 'user'){
                        $this->redirect('/dashboard');
                    }
                    elseif($role == 'admin'){
                        $this->redirect('/admin-panel');
                    }
                }   
            }

        }
    
        public function index(){
            $this->render('login/signIn',['currentPage' => 'login']);   
        }


        public function signIn(){

            $temp = $this->prepareValidations([
                'email' =>['required','email'],
                'password' => ['required','min:8','max:16']
            ]);

            $validator = new Validator;
            $errorMessages = $validator->validate($temp[0], $temp[1]);
            

            if(count($errorMessages) > 0){
                $_SESSION['errors'] = $errorMessages;
                $this->redirect('/login');
            } 



            $email = $_POST['email'];
            $pass = $_POST['password'];

            $user = new User;

            if(!$user->findByEmail($email) or !password_verify($pass, $user->password)){
                $_SESSION['errors'] = ['Incorrect email or password'];
                $this->redirect('/login');
            }

            $_SESSION['user_id'] = $user->id;
            if($user->rol == 'user'){
                $this->redirect('/dashboard');
                
            }
            elseif($user->rol == 'admin'){
                $this->redirect('/admin-panel');
            
            }

        }

        public function logOut(){
            session_destroy();
            $this->redirect('/login');    
        }

    }