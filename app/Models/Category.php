<?php
    namespace app\Models;
    use app\Classes\Model;
    use PDOException;
    use PDO;

    class Category extends Model{

        public function __construct()
        {
            parent::__construct();
            $this->table = 'categories';
            $this->columns = ['id','name','color'];
            $this->attributesGenerator();
        }


        public function fill (array $data){
            $this->id = $data['id'];
            $this->name = $data['name'];
            $this->color = $data['color'];
        }

        public function get_expenses (){
            try {
                $query = $this->pdo->prepare('SELECT * FROM expenses WHERE category_id = ?');
                $query->execute([$this->id]);

                $items = [];
                if($query->rowCount() > 0 ){
                    while ($item = $query->fetch(PDO::FETCH_ASSOC)){
                        $expense = new Expense;
                        $expense->fill($item);
                        array_push($items,$expense);
                    }
                }
                return $items;    
            }
            catch (PDOException $e) {
                throw $e;
            } 
        }
        
        public function getExpensesByMonth($user_id, $month){
            try {
                $query = $this->pdo->prepare('SELECT * FROM expenses WHERE user_id = ? AND category_id = ? AND MONTH(date) = ?');
                $query->execute([$user_id, $this->id,$month]);

                $items = [];
                if($query->rowCount() > 0 ){
                    while ($item = $query->fetch(PDO::FETCH_ASSOC)){
                        $expense = new Expense;
                        $expense->fill($item);
                        array_push($items,$expense);
                    }
                }
                return $items;    
            }
            catch (PDOException $e) {
                throw $e;
            }
        }


        public function findByName($name){
            try {
                $query = $this->pdo->prepare('SELECT * FROM categories WHERE name = ?');
                $query->execute([$name]);

                return $query->rowCount();    
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

        public function limit($pointer, $amount){
            try {
                $limit = 'LIMIT '.$pointer.','.$amount;
                $query = $this->pdo->prepare('SELECT * FROM categories ORDER BY id DESC '.$limit);
                $query->execute();
            
                $items = [];
                if($query->rowCount() > 0 ){
                    while ($item = $query->fetch(PDO::FETCH_ASSOC)){
                        $category = new Category;
                        $category->fill($item);
                        array_push($items,$category);
                    }
                }
                return $items;    
            }
            catch (PDOException $e) {
                throw $e;
            }
        }

    }