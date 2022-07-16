<?php
    namespace app\Controllers;
    use app\Classes\Controller;
    use app\Models\Category as categoryModel;
    use app\Classes\Validator;
    use app\Models\User;
    use Exception;

    class Category extends Controller{

        private $category;
        
        public function __construct()
        {

            parent::__construct();
            $this->user = new User;
            $this->user->find($_SESSION['user_id']);
            $this->role = $this->user->rol;

            if($this->role == 'user'){
                $this->redirect('/dashboard');
            }

            $this->category = new categoryModel;

        }


        public function index(){
            $this->render('categories/index',['role' => $this->role]);
        }

        public function categoriesData($pointer, $amount){
            $data = array();
            $categories = $this->category->limit($pointer, $amount);
            $totalCategories = count($this->category->get_all());

            $data['user-data'] = [
                'user_name' => $this->user->name,
                'photo' => $this->user->photo,
            ];      
            $data['categories'] = $categories;
            $data['total'] = $totalCategories;

            $this->json_response('200', $data);
        }

        public function edit(){

            $errorMessages = array();

            switch(true){
                case !isset($_GET['id']):
                    array_push($errorMessages, 'The field id is required');
                    break;
                
                case !intval($_GET['id']):
                    array_push($errorMessages, 'Invalid id');
                    break;
            }

            if(count($errorMessages) == 0){
                $id = $_GET['id'];
                if($this->category->find($id)){
                    $this->render('categories/edit',['role' => $this->role, 'category' => $this->category]);
                    exit();    
                }
                else{
                    array_push($errorMessages, 'Category not found');
                } 
            }
            
            $_SESSION['Me$errorMessages'] = $errorMessages;
            $this->redirect('/categories');     
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

        public function update(){

            $temp = $this->prepareValidations([
                'id' => ['required', 'numeric'],
                'name' => ['required','min:3','max:15'],
                'color' => ['required','color']  
            ]);

            $validator = new Validator;
            $errorMessages = $validator->validate($temp[0], $temp[1]);


            if(count($errorMessages) == 0){
                if(!$this->category->find($_POST['id'])){
                    array_push($errorMessages, ['Category no found']);
                }
                else{
                    $this->category->name = $_POST['name'];
                    $this->category->color = $_POST['color'];

                    if(!$this->category->update()){
                        array_push($errorMessages,'An unknown error occurred, try again later');
                    } 
                }
            }

            if(count($errorMessages) > 0){
                $_SESSION['errors'] = $errorMessages;
            }
            else{
                $_SESSION['success'] = 'Category updated';
            }
   
            $this->redirect('/categories');
        }

        public function delete(){

            $temp = $this->prepareValidations([
                'id' => ['required','numeric'],
            ]);

            $validator = new Validator;
            $errorMessages = $validator->validate($temp[0], $temp[1]);

            if(count($errorMessages) > 0){
                $this->json_response(400, ['messages' => $errorMessages]);
            }

            $id = $_POST['id'];
            $isOk = $this->category->delete($id);

            if($isOk){
                $this->json_response('201',['messages' => 'Category deleted']);
            }
            else{
                $this->json_response('400',['messages' => 'An unknown error occurred, try again later']);
            }     

        }


    }