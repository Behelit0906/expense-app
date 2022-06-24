<?php
    namespace app\Models;
    use app\Classes\Model;
    use PDO;
    use PDOException;

    class User extends Model{

        public $id;
        public $name;
        public $email;
        public $password;
        public $rol;
        public $photo;
        public $budget;

        public function __construct(){
            parent::__construct();
            $this->id = null;
            $this->name = '';
            $this->email = '';
            $this->password = '';
            $this->rol = '';
            $this->photo = '';
            $this->budget = null; 
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

        public function store (){
            try{
                $query = $this->pdo->prepare('INSERT INTO users 
                (name, email, password, rol, photo, budget) 
                VALUES (?,?,?,?,?,?)');

                $query->execute([
                    $this->name,
                    $this->email,
                    $this->password,
                    $this->rol,
                    $this->photo,
                    $this->budget
                ]);

                return $query;

            }
            catch(PDOException $e){
                throw $e;
            }
        }

        public function get ($id){
            try{
                $query = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
                $query->execute([$id]);

                if($query->rowCount() > 0){
                    $query = $query->fetch(PDO::FETCH_ASSOC);

                    $this->id = $query['id'];
                    $this->name = $query['name'];
                    $this->email = $query['email'];
                    $this->password = $query['password'];
                    $this->rol = $query['rol'];
                    $this->photo = $query['photo'];
                    $this->budget = $query['budget'];

                    return $this;
                }

                return false;
            }
            catch(PDOException $e){
                throw $e;
            }
        }


        public function update (){
            try{
                $query = $this->pdo->prepare('UPDATE users SET 
                name=?, email=?,password=?,rol=?, photo=?,budget=?');

                $query->execute([
                    $this->name,
                    $this->email,
                    $this->password,
                    $this->rol,
                    $this->photo,
                    $this->budget
                ]);

                return $query;

            }
            catch(PDOException $e){
                throw $e;
            }
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