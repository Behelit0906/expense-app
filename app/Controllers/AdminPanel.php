<?php
    namespace app\Controllers;
    use app\Classes\Controller;
    use app\Models\User;
    use app\Models\Expense;
    use app\Models\Category;

    class AdminPanel extends Controller{

        private $user;
        private $expense;
        private $category;


        public function __construct()
        {
            parent::__construct();

            $role = $this->checkRole($_SESSION['user_id']);
            if($role == 'user'){
                $this->redirect('/dashboard');
            }

            $this->user = new User;
            $this->expense = new Expense;
            $this->category = new Category;

        }


        public function index(){

            


            $this->render('admin/index',['currentPage' => 'dashboard']);
        }


        private function prepareData(){

            $categories_data = $this->mostAndLeastUsedCategory();

            $data = [
                'card-data' => [
                    [
                        'category' => 'Users',
                        'statistics' => $this->users(),
                        'footer' => 'registered users'
                    ],
                    [
                        'category' => 'Expenses',
                        'statistics' => $this->expenses(),
                        'footer' => 'Expenses'
                    ],
                    [
                        'category' => 'Expenses',
                        'statistics' => $this->higherExpense(),
                        'footer' => 'Higher expense'
                    ],
                    [
                        'category' => 'Expenses',
                        'statistics' => $this->minimumExpense(),
                        'footer' => 'Minimum expense'
                    ],
                    [
                        'category' => 'Expenses',
                        'statistics' => $this->averageExpense(),
                        'footer' => 'Average expense'
                    ],
                    [
                        'category' => 'Categories',
                        'statistics' => $this->categories(),
                        'footer' => 'Categories created'
                    ],
                    [
                        'category' => 'Categories',
                        'statistics' => $categories_data[1],
                        'footer' => 'Most popular category'
                    ],
                    [
                        'category' => 'Categories',
                        'statistics' => $categories_data[0],
                        'footer' => 'Least popular category'
                    ],
                ],
                'currentPage' => 'admin-panel' 
            ];
        }

        private function users(){
            return count($this->user->get_all());
        }

        private function expenses(){
            return count($this->expense->get_all());
        }

        private function higherExpense(){
            return $this->expense->higherExpenseOfAll();
        }

        private function minimumExpense(){
            return $this->expense->minimumExpense();
        }

        private function averageExpense(){
            $expenses = $this->expense->get_all();
            $average = 0;

            foreach($expenses as $expense){
                $average += $expense->amount;
            }
            return $average;
        }

        private function categories(){
            return count($this->category->get_all());
        }

        private function mostAndLeastUsedCategory(){
            $categories = $this->category->get_all();
        
            $accounts = [];

            foreach($categories as $category){
                array_push($accounts,[count($category->get_expenses()) => $category->name]);            
            }

            $max = 0;
            $min = 0;

            foreach($accounts as $key => $value){
                if($min == 0){
                    $min = $key;
                }
                
                if($key > $max){
                    $max = $value;
                }
                elseif($key < $min){
                    $min = $key;
                }
            }

            return [$accounts[$min], $accounts[$max]];
        }



    }
