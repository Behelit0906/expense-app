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

    }