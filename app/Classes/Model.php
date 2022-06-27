<?php
    namespace app\Classes;
    use app\Classes\Database;
    use PDOException;
    use PDO;

    class Model {
        protected $pdo;
        protected $table;
        protected $columns;

        public function __construct()
        {
            $this->pdo = Database::connect();
        }

        //generación de atributos en base a los nombres de las columnas de la tabla user
        protected function attributesGenerator(){
            foreach($this->columns as $column){
                $this->$column = '';
            }
        }


        //Preparación de la cadena para el query INSERT
        private function prepareStoreQuery(){
            //Primera parte de la sentencia SQL
            $query = 'INSERT INTO '.$this->table.' (';

            //añado los nombres de las columnas a la primera parte de la cadena 
            $count = count($this->columns);
            for($i = 1; $i < $count; $i++){
                if($i != $count - 1){
                    $query.=$this->columns[$i].', ';
                }
                else{
                    $query.=$this->columns[$i].') VALUES (';
                }
            }
                
            //Añado los '?' a la cadena para los valores
            for($i = 1; $i < $count; $i++){
                if($i != $count - 1){
                    $query.='?,';
                }
                else{
                    $query.='?)';
                }
            }
                return $query;
        }


        //Preparación de la cadena para el query UPDATE
        private function prepareUpdateQuery(){
            $query = 'UPDATE '.$this->table.' SET ';

            $count = count($this->columns);
            for($i = $count - 1; $i >= 0; $i--){
                if($i != 1){
                    $query.=$this->columns[$i].'=?, ';
                }
                else{
                    $query.=$this->columns[1].'=? WHERE id=?';
                    break;
                }
            }

            return $query;

        }


        public function get_all(){
            try {
                $query = $this->pdo->prepare('SELECT * FROM '.$this->table);
                $query->execute();
                

                if($query->rowCount() > 0){
                    $items = [];

                    while($item = $query->fetch(PDO::FETCH_ASSOC)){
                        foreach($this->columns as $column){
                            $this->$column = $item[$column];
                        }
                        array_push($items,$this);
                    }    
                }

                return $items;
            } 
            catch (PDOException $e) {
                throw $e;
            }
        }

        public function find($id){
            try {
                $query = $this->pdo->prepare('SELECT * FROM '.$this->table.' WHERE id=?');
                $query->execute([$id]);

                if ($query->rowCount() > 0){
                    $query = $query->fetch(PDO::FETCH_ASSOC);

                    $this->fill($query);

                    return $this;
                }
                return false;
            } 
            catch (PDOException $e) {
                throw $e;
            }
        }

  
        public function store(){
            try{ 
                $query = $this->prepareStoreQuery();
                $query = $this->pdo->prepare($query);

                $data =[];
                $count = count($this->columns);
                for($i = 1; $i < $count; $i++){
                    $temp = $this->columns[$i];
                    array_push($data, $this->$temp);
                }
                
                $query->execute($data);
                return $query->rowCount() > 0 ? true:false;

            }
            catch(PDOException $e){
                throw $e;
            }
        }


        public function update(){
            try{ 
                $query = $this->prepareUpdateQuery();
                $query = $this->pdo->prepare($query);

                $data =[];
                $count = count($this->columns);
                for($i = $count - 1; $i >= 0; $i--){
                    $temp = $this->columns[$i];
                    array_push($data, $this->$temp);
                }
                
                $query->execute($data);
                return $query->rowCount() > 0 ? true:false;
            }
            catch(PDOException $e){
                throw $e;
            }
        }

        public function delete($id){
            try{
                $query = $this->pdo->prepare('DELETE FROM '.$this->table.' WHERE id=?');
                $query->execute([$id]);

                return $query->rowCount() > 0 ? true:false;
            }
            catch(PDOException $e){
                throw $e;
            }
        }

    }