<?php
    namespace app\Controllers;

    use app\Classes\Controller;
    use app\Models\User;
    use app\Classes\Validator;


    class SignIn extends Controller{

        private $user;

        public function __construct()
        {
            parent::__construct();

            $this->user = new User;
            if(isset($_SESSION['user_id'])){
                $this->user->find($_SESSION['user_id']);
                $role = $this->user->rol;

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

            

            if(!$this->user->findByEmail($email) or !password_verify($pass, $this->user->password)){
                $_SESSION['errors'] = ['Incorrect email or password'];
                $this->redirect('/login');
            }

            $_SESSION['user_id'] = $this->user->id;
            if($this->user->rol == 'user'){
                $this->redirect('/dashboard');
                
            }
            elseif($this->user->rol == 'admin'){
                $this->redirect('/admin-panel');
            }

        }

        public function logOut(){
            session_destroy();
            $this->redirect('/login');    
        }

    }