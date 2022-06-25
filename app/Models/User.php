<?php
    namespace app\Models;
    use app\Classes\Model;
    use PDO;
    use PDOException;

    class User extends Model{

        public function __construct(){
            parent::__construct();
            $this->table = 'users';
            $this->columns = [
                'id','name','email','password','rol','photo','budget'
            ];
            $this->attributesGenerator();

            $this->rol = 'user';
            $this->budget = 0;
        }
        
        
        public function fill (array $data){
            $this->id = $data['id'];
            $this->name = $data['name'];
            $this->email = $data['email'];
            $this->password = $data['password'];
            $this->rol = $data['rol'];
            $this->photo = $data['photo'];
            $this->budget = $data['budget'];
        }



        public function get_expenses (){
            try {
                $query = $this->pdo->prepare('SELECT * FROM expenses WHERE user_id = ?');
                $query->execute([$this->id]);

                if($query->rowCount() > 0 ){

                }
                return false;    
            }
            catch (PDOException $e) {
                throw $e;
            } 
        }

    }