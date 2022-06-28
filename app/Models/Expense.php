<?php
    namespace app\Models;
    use app\Classes\Model;
    use app\Models\User;
    use app\Models\Category;
    use PDO;
    use PDOException;

    class Expense extends Model{

        public function __construct()
        {
            parent::__construct();

            $this->table = 'expenses';
            $this->columns = [
                'id','name','amount','user_id','category_id'
            ];
            $this->attributesGenerator();
        }
        
        public function fill(array $data)
        { 
            $this->id = $data['id'];
            $this->name = $data['name'];
            $this->amount = $data['amount'];
            $this->user_id = $data['user_id'];
            $this->category_id = $data['category_id'];
        }


        public function belongToUser(){
            try{
                $query = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
                $query->execute([$this->user_id]);

                if($query->rowCount() > 0){
                    $user = new User;
                    $user->fill($query->fetch(PDO::FETCH_ASSOC));

                    return $user;
                }
                return false;
            }
            catch(PDOException $e){
                throw $e;
            }
        }

        public function belongToCategory(){
            try{
                $query = $this->pdo->prepare('SELECT * FROM categories WHERE id = ?');
                $query->execute([$this->category_id]);

                if($query->rowCount() > 0){
                    $category = new Category;
                    $category->fill($query->fetch(PDO::FETCH_ASSOC));
                    return $category;
                }
                return false;
            }
            catch(PDOException $e){
                throw $e;
            }
        }






    }