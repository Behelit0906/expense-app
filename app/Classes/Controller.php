<?php
    namespace app\Classes;
    use app\Classes\Validator;
    use Twig\Loader\FilesystemLoader;
    use Twig\Environment;
    use Twig\TwigFunction;
    use app\Models\User;

    class Controller{

        private $twig;

        public function __construct()
        {
            $loader = new FilesystemLoader('app/Views/');
            $this->twig = new Environment($loader);     
            $function= new TwigFunction('session', function ($name){
                return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
            });
            $cleanFunction = new TwigFunction('sessionUnset',function($name){
                if(isset($_SESSION[$name])){
                    unset($_SESSION[$name]);
                }
            });
            $this->twig->addFunction($function);
            $this->twig->addFunction($cleanFunction);
        }

        protected function render($view, $data = []){
            $view.='.html';
            $data['URL'] = URL;     
            echo $this->twig->render($view,$data); 
        }

        

        //validador para datos de formularios
        protected function prepareValidations($rules){
            $data = [];
            $validationRules = [];
            foreach($rules as $key => $value){
                $data[$key] = $_POST[$key];
                $validationRules[$key] = $value;
            }

            return [$data, $validationRules];  
        }


        protected function redirect($page){
            header('Location:'.URL.$page);
            exit();
        }

        protected function json_response($statusCode, $data){
            header('Content-Type: application/json');
            $response = array();

            $response['status-code'] = $statusCode;
            foreach($data as $key => $value){
                $response[$key] = $value;
            }

            echo json_encode($response);
            exit();
        }

        protected function checkRole($id){
            $user = new User;
            if($user->find($id)){
                return $user->rol;
            }
            return false;    
        }
    }