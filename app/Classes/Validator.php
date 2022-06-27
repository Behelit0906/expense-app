<?php
    namespace app\Classes;

    class Validator{

        private $validations;
        private $errorMessages;

        public function __construct()
        {
            $this->validations = [];
            $this->errorMessages = [];
        }
        
        //Se le pasan dos arrays asociativos, uno con la información a evaluar y el otro con las
        //reglas con las que se evaluaran
        public function validate($data, $validationRules){
            $this->prepare($validationRules);
            

            //Recorro el array de validaciones previamente preparado con el metodo prepare
            foreach($this->validations as $key => $value){ //la key es el nombre del campo a evaluar y value el array de arrays con las regla
                foreach($value as $rule){ //cada elemento rule es un array con el nombre del metodo y el parametro principal
                    $trigger = false;
                    if(count($rule) == 1){ //Si el array solo tiene un elemento es un metodo de comprobación que no requiere parametro principal (required, email)
                        $trigger = call_user_func_array([$this,$rule[0]],[$data[$key],$key]); //max:10 -> el parametro prinicipal sería el 10
                    }
                    elseif(count($rule) == 2){//Si tiene dos entonces sí tiene parametro principal max:10, min:3 etc.
                        $trigger = call_user_func_array([$this,$rule[0]],[$rule[1],$data[$key],$key]);
                    }

                    //Si no se cumple con una condición rompo el bucle para no seguir comprobando ese campo
                    if($trigger){
                        break;
                    }
                }
            }

            return $this->errorMessages;
        }

        //divido los nombres de los parametros en la cadena de las reglas pasadas
        // 'max:3' => ['max',3] quedando un array donde las keys son el nombre
        //del campo a evaluar y su valor un array de arrays con los nombres de los metodos y
        //parametros para evaluarlos
        //'name' => [['min',3],['max',10]],
        //'email' => [['required'],['email']]

        private function prepare($validationRules){
            foreach($validationRules as $key => $values){
                $temp = 0;
                $this->validations[$key] = [];
                foreach($values as $value){
                    $index = strpos($value,':');
                    if($index){
                        $name = substr($value,0,$index);
                        $param = substr($value,$index+1);
                        array_push($this->validations[$key],[$name,$param]);
                    }
                    else{
                        array_push($this->validations[$key],[$value]);
                    }
                }
            }
        }


        //metodos para comprabar que un campo cumple una condición
        private function required($string,$fieldName){
            if(!$string != ''){
                array_push($this->errorMessages,'The field '.$fieldName.' is required');
                return true;
            }
        }

        private function email($string,$fieldName){
            if(!filter_var($string, FILTER_VALIDATE_EMAIL)){
                array_push($this->errorMessages,'Enter a valid email address');
                return true;    
            }
                 
        }

        private function numeric($string,$fieldName){
            if(!is_numeric($string)){
                array_push($this->errorMessages,'The data in the '.$fieldName.' field must be numeric'); 
                return true;   
            }
        }

        private function max($limit, $string, $fieldName){
            
            if(strlen($string) >  $limit){
                array_push($this->errorMessages,'The '.$fieldName.' must have a maximum of '.$limit.' characters');
                return true;
            }
        }

        private function min($limit, $string,$fieldName){
            echo $limit;
                echo $string;
            if(strlen($string) < $limit){
                array_push($this->errorMessages,'The '.$fieldName.' must have a minimum of '.$limit.' characters');
                return true;
            }
        }

        private function password_confirmation($password, $fieldName){
            if($password != $_POST['password_confirmation']){
                array_push($this->errorMessages,'Passwords do not match');
                return true;
            }
        }
        

    }

    