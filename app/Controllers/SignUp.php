<?php
    namespace app\Controllers;
    use app\Classes\Controller;
    use app\Models\User;
    use app\Classes\Validator;

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
            $this->render('login/signUp',['currentPage' => 'login']);
        }
        
        public function signUp(){
            $temp = $this->prepareValidations([
                'name' => ['required','min:3','max:20'],
                'email' =>['required','email'],
                'password' => ['required','min:8','max:16','password_confirmation']
            ]);

            $validator = new Validator;
            $errorMessages = $validator->validate($temp[0], $temp[1]);
            

            if(count($errorMessages) > 0){
                $_SESSION['errors'] = $errorMessages;
                $this->redirect($_SERVER['HTTP_REFERER']);
                exit();
            }

            $email = $_POST['email'];
            $user = new User;
            if($user->findByEmail($email)){
                array_push($errorMessages,'Email is already registered');
                $_SESSION['errors'] = $errorMessages;
                $this->redirect($_SERVER['HTTP_REFERER']);
                exit();
            }


            $user->name = $_POST['name'];
            $user->email = $email;
            $user->password = password_hash($_POST['password'],PASSWORD_BCRYPT);
            $user->store();

            $_SESSION['success'] = 'Successful registration';
            $this->redirect('http://your-expenses.com/login');
            exit();      
        }

    }