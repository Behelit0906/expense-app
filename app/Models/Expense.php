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
                'id','name','amount','date','user_id','category_id'
            ];
            $this->attributesGenerator();
            $this->date = date('Y-m-d');
        }
        
        public function fill(array $data)
        { 
            $this->id = $data['id'];
            $this->name = $data['name'];
            $this->amount = $data['amount'];
            $this->date = $data['date'];
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

        public function biggestExpenseThisMonth($user_id,$month){
            try{
                $query = $this->pdo->prepare('SELECT * FROM expenses 
                WHERE user_id = ? AND MONTH(date) = ? ORDER BY amount DESC LIMIT 1');
                $query->execute([$user_id, $month]);

                if($query->rowCount() > 0){
                    $query = $query->fetch(PDO::FETCH_ASSOC);
                    return intval($query['amount']);
                }
                
                return 0;
            }
            catch(PDOException $e){
                throw $e;
            }
        }

        public function limitedSelect($user_id,$amount){
            try{
                $query = $this->pdo->prepare('SELECT * FROM expenses WHERE user_id = ? 
                ORDER BY id DESC LIMIT '.$amount);
                $query->execute([$user_id]);

                $items = [];
                if($query->rowCount() > 0){
                    while($item = $query->fetch(PDO::FETCH_ASSOC)){
                        $expense = new Expense;
                        $expense->fill($item);
                        array_push($items,$expense);
                    }
                }
                return $items;

            }
            catch(PDOException $e){
                throw $e;
            }
        }

        public function getExpensesThisMonth($user_id, $month){
            try {
                $query = $this->pdo->prepare('SELECT * FROM expenses WHERE user_id = ? AND MONTH(date) = ?');
                $query->execute([$user_id, $month]);

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






    }