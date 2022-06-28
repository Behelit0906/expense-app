<?php
    namespace app\Classes;
    use app\Classes\Validator;
    use Twig\Loader\FilesystemLoader;
    use Twig\Environment;
    use Twig\TwigFunction;

    class Controller{

        private $twig;

        public function __construct()
        {
            $loader = new FilesystemLoader('app/Views/');
            $this->twig = new Environment($loader);     
            $function= new TwigFunction('errors', function (){
                return isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
            });
            $cleanFunction = new TwigFunction('cleaner',function(){
                if(isset($_SESSION['errors'])){
                    unset($_SESSION['errors']);
                }
            });
            $this->twig->addFunction($function);
            $this->twig->addFunction($cleanFunction);
        }

        protected function render($view, $data = []){
            $view.='.html';     
            echo $this->twig->render($view,$data); 
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
            $errorMessages = $validator->validate($data, $validationRules);
            

            if(count($errorMessages) > 0){
                $_SESSION['errors'] = $errorMessages;
                $this->redirect($_SERVER['HTTP_REFERER']);
            }   
        }


        protected function redirect($page){
            header('Location:'.$page);
            exit();
        }
    }