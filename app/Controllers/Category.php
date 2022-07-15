<?php
    namespace app\Controllers;
    use app\Classes\Controller;
    use app\Models\Category as categoryModel;
    use app\Classes\Validator;
    use app\Models\User;

    class Category extends Controller{

        private $category;
        
        public function __construct()
        {

            parent::__construct();
            $this->user = new User;
            $this->user->find($_SESSION['user_id']);
            $this->role = $this->user->rol;

            // if($this->role == 'user'){
            //     $this->redirect('/dashboard');
            // }

            $this->category = new categoryModel;

        }



        public function save(){


            $temp = $this->prepareValidations([
                'name' => ['required','min:3','max:15'],
                'color' => ['required','color']  
            ]);
        
            $validator = new Validator;
            $errorMessages = $validator->validate($temp[0], $temp[1]);

            if(count($errorMessages) > 0){
                $this->json_response(400,['messages' => $errorMessages]);
            }

            $name = ucfirst(strtolower($_POST['name']));
            $color = $_POST['color'];

            if($this->category->findByName($name)){
                $this->json_response(400,['messages' => ['A category with this name already exists']]);
            }


            $this->category->name = $name;
            $this->category->color = $color;
            $this->category->store();
            
            $this->json_response(201,['messages' => ['Category registered']]);
        }


    }