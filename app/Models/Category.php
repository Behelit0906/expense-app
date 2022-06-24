<?php
    namespace app\Models;
    use app\Classes\Model;
    use PDO;
    use PDOException;

    class Category extends Model{

        public $id;
        public $name;
        public $color;

        public function __construct()
        {
            parent::__construct();
            $this->id = null;
            $this->name = '';
            $this->color = '';
        }


        public function fill (array $data){
            $this->id = $data['id'];
            $this->name = $data['name'];
            $this->color = $data['color'];
        }

        public function store (){
            try{
                $query = $this->pdo->prepare('INSERT INTO categories (name, color) VALUES (?,?)');
                $query->execute([
                    $this->name,
                    $this->color
                ]);

                return $query;

            }
            catch(PDOException $e){
                throw $e;
            }
        }

        public function get ($id){
            try {
                $query = $this->pdo->prepare('SELECT * FROM categories WHERE id = ?');
                $query->execute([$this->id]);

                if($query->rowCount() > 0){
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


        public function update (){
            try{
                $query = $this->pdo->prepare('UPDATE categories SET name=?,color=?');
                $query->execute([
                    $this->name,
                    $this->color
                ]);

                return $query;

            }
            catch(PDOException $e){
                throw $e;
            }
        }


        public function get_expenses (){
            try {
                $query = $this->pdo->prepare('SELECT * FROM expenses WHERE category_id = ?');
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