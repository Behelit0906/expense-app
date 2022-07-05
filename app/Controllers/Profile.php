<?php
    namespace app\Controllers;
    use app\Classes\Controller;
    use app\Models\User;
    use app\Classes\Validator;

    class Profile extends Controller{

        private $user;

        public function __construct()
        {
            parent::__construct();

            $this->user = new User;
            $this->user->find($_SESSION['user_id']);
        }


        public function index(){
            $this->render('profile/index',['currentPage' => 'profile']);
        }

        public function userData(){

            $data = [
                'name' => $this->name,
                'budget' => $this->budget,
                'email' => $this->email,
                'photo' => $this->photo
            ];

            header('Content-Type: application/json');
            echo json_encode($data);
            exit();
        }

        public function updateName(){
            header('Content-Type: application/json');

            if(isset($_POST['name'])){
                $temp = $this->prepareValidations([
                    'name' => ['required','min:3','max:15']  
                ]);

                $validator = new Validator;
                $errorMessages = $validator->validate($temp[0], $temp[1]);

                if(count($errorMessages) > 0){
                    echo json_encode(["errors" => $errorMessages]);
                }

                $this->user->name = $_POST['name'];
                $this->user->update();

                echo json_encode(["success" => "Name updated"]);
                

            }
            else{
                echo json_encode(['errors' => 'The request does not contain a name field']);       
            }

            exit();
        }

        public function updateBudget(){
            header('Content-Type: application/json');

            if(isset($_POST['budget'])){
                $temp = $this->prepareValidations([
                    'budget' => ['required','numeric'], 
                ]);

                $validator = new Validator;
                $errorMessages = $validator->validate($temp[0], $temp[1]);

                if(count($errorMessages) > 0){
                    echo json_encode(["errors" => $errorMessages]);
                }

                $this->user->budget = floatval($_POST['budget']);
                $this->user->update();

                echo json_encode(["success" => "Budget updated"]);
                

            }
            else{
                echo json_encode(['errors' => 'The request does not contain a budget field']);   
            }

            exit();
        }

        public function updatePassword(){
            header('Content-Type: application/json');

            if(isset($_POST['password']) and isset($_POST['password_confirmation'])){
                $temp = $this->prepareValidations([
                    'password' => ['required','min:8','max:16','password_confirmation']
                ]);

                $validator = new Validator;
                $errorMessages = $validator->validate($temp[0], $temp[1]);

                if(count($errorMessages) > 0){
                    echo json_encode(["errors" => $errorMessages]);
                    
                }

                $this->user->password = password_hash($_POST['password'],PASSWORD_BCRYPT);
                $this->user->update();

                echo json_encode(["success" => "Password updated"]);
                

            }
            else{
                echo json_encode(['errors' => 'The request does not contain a password field']);
                
            }

            exit();
        }

        public function updateImage(){
            
            $directory = 'app/storage/profile-pictures/';
            $errors = [];

            $imageName = $_FILES['profile-image']['name'];

            if(!empty($imageName)){
                $extensions = ['jpg','jpeg','png'];
                $imageExt = strtolower(pathinfo($imageName,PATHINFO_EXTENSION));

                if(!in_array($imageExt, $extensions)){
                    array_push($errors,'Invalid image format');
                }

                $imageSize = $_FILES['profile-image']['size'];

                if($imageSize > 3145728){
                    array_push($errors,'Image too heavy (maximum 3 MB)');
                }
                
                if(count($errors) == 0){
                    $file = $directory.md5(date('c').$imageName).'.'.$imageExt;
                    if(move_uploaded_file($_FILES['profile-image']['tmp_name'], $file)){
                        echo json_encode(['success' => 'Photo update']);
                    }
                    else{
                        echo json_encode(['errors' => ['An error has occurred, try again later']]);
                    }

                }
                else{
                    echo json_encode(['errors' => $errors]);
                }

                exit();

            }
            

        }

    }