<?php
    namespace app\Classes;
    use app\Classes\Validator;

    class Controller{

        protected $model;
        protected $validateMessages;

        public function __construct()
        {
            $this->validateMessages = [];
        }

        protected function render($view, $data = []){
            require('app/Views/'.$view.'.php');
        }

        //validador para datos de formularios
        protected function validate($rules){
            $data = [];
            $validationRules = [];
            foreach($rules as $key => $value){
                $data[$key] = $_POST[$key];
                $validationRules[$key] = $value;
            }

            $validator = new Validator;
            $this->validateMessages = $validator->validate($data, $validationRules);

            if(count($this->validateMessages) > 0){
                $_SESSION['validateErrors'] = $this->validateMessages;
                $this->redirect($_SERVER['HTTP_REFERER']);
            }   
        }


        protected function redirect($page){
            header('Location:'.$page);
            exit();
        }
    }