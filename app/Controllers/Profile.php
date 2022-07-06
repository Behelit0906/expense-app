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
                'name' => $this->user->name,
                'budget' => $this->user->budget,
                'email' => $this->user->email,
                'photo' => $this->user->photo
            ];

            
            $this->json_response(201,['user-data' => $data]);
        }

        public function updateName(){

            $messages = array ();

            if(isset($_POST['name'])){
                $temp = $this->prepareValidations([
                    'name' => ['required','min:3','max:15']  
                ]);

                $validator = new Validator;
                $errorMessages = $validator->validate($temp[0], $temp[1]);

                if(count($errorMessages) > 0){
                    $messages = $errorMessages;
                }
                else{
                    $this->user->name = $_POST['name'];
                    $this->user->update();
                    $this->json_response(201, ['Name updated']);
                }
            }
            else{
                array_push($messages,'The name fied was not found in the request');       
            }

            $this->json_response(400, ['messages' => $messages]);
        }

        public function updateBudget(){

            $messages = array ();

            if(isset($_POST['budget'])){
                $temp = $this->prepareValidations([
                    'budget' => ['required','numeric'], 
                ]);

                $validator = new Validator;
                $errorMessages = $validator->validate($temp[0], $temp[1]);

                if(count($errorMessages) > 0){
                    $messages = $errorMessages;
                }
                else{
                    $this->user->budget = floatval($_POST['budget']);
                    $this->user->update();
                    $this->json_response(201,['Budget updated']);
                }
            }
            else{
                array_push($messages,'The budget field is required');  
            }

            $this->json_response(400, ['messages' => $messages]);
        }

        public function updatePassword(){

            $messages = array ();

            if(isset($_POST['password']) and isset($_POST['password_confirmation'])){
                $temp = $this->prepareValidations([
                    'password' => ['required','min:8','max:16','password_confirmation']
                ]);

                $validator = new Validator;
                $errorMessages = $validator->validate($temp[0], $temp[1]);

                if(count($errorMessages) > 0){
                    $messages = $errorMessages;    
                }
                else{
                    $this->user->password = password_hash($_POST['password'],PASSWORD_BCRYPT);
                    $this->user->update();
                    $this->json_response(201,['Password updated']);
                }
            }
            else{
                array_push($messages, 'the password and password_confirmation fields were not found in request');  
            }

            $this->json_response(400, ['messages' => $messages]);
        }

        public function updateImage(){

            $directory = 'app/storage/profile-pictures/';
            $messages = array ();

            //Valid if the profile-image field is in the request.
            if(isset($_FILES['profile-image'])){
                $imageName = $_FILES['profile-image']['name'];
            

                //That it is not empty
                if(!empty($imageName)){
                    $extensions = ['jpg','jpeg','png'];
                    $imageExt = strtolower(pathinfo($imageName,PATHINFO_EXTENSION));

                    //Validating extension
                    if(!in_array($imageExt, $extensions)){
                        array_push($messages,'Incorrect image format (jpg, jpeg, png)');
                    }
                    else{
                        $imageSize = $_FILES['profile-image']['size'];


                        //Validating maximum size
                        if($imageSize > 3145728){
                            array_push($errors,'Image too heavy (maximum 3 MB)');
                        }
                        else{
                            $file = $directory.md5(date('c').$imageName).'.'.$imageExt;

                            //Validating that it is stored
                            if(move_uploaded_file($_FILES['profile-image']['tmp_name'], $file)){
                                $this->json_response(201,['Profile image updated']);
                            }
                            else{
                                array_push($messages, 'An unknown error occurred, try again later');
                            }
                        }
                    }
                }
                else{
                    array_push($messages,'The profile-image field is empty');
                }  
            }
            else{
                array_push($messages,'The profile-image field was not found in the request');
            }
    
            
            $this->json_response(400, ['messages' => $messages]);

        }

    }