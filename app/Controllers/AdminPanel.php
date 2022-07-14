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
        private $role;


        public function __construct()
        {
            parent::__construct();

            $this->user = new User;
            $this->user->find($_SESSION['user_id']);
            $this->role = $this->user->rol;
            if($this->role == 'user'){
                $this->redirect('/dashboard');
            }

            $this->expense = new Expense;
            $this->category = new Category;

        }


        public function index(){
            $data = $this->prepareData();
            $data['role'] = $this->role;
            $data['user-data'] = [
                'user_name' => $this->user->name,
                'photo' => $this->user->photo
            ];
            $this->render('admin/index',$data);
        }


        private function prepareData(){

            $categories_data = $this->mostAndLeastUsedCategory();

            $data = [
                'cards_data' => [
                    [
                        'category' => 'Users',
                        'statistic' => $this->users(),
                        'footer' => 'Registered users'
                    ],
                    [
                        'category' => 'Expenses',
                        'statistic' => $this->expenses(),
                        'footer' => 'Transactions'
                    ],
                    [
                        'category' => 'Expenses',
                        'statistic' => $this->higherExpense(),
                        'footer' => 'Higher expense'
                    ],
                    [
                        'category' => 'Expenses',
                        'statistic' => $this->minimumExpense(),
                        'footer' => 'Minimum expense'
                    ],
                    [
                        'category' => 'Expenses',
                        'statistic' => $this->averageExpense(),
                        'footer' => 'Average expense'
                    ],
                    [
                        'category' => 'Categories',
                        'statistic' => $this->categories(),
                        'footer' => 'Categories created'
                    ],
                    [
                        'category' => 'Categories',
                        'statistic' => $categories_data[1],
                        'footer' => 'Most popular category'
                    ],
                    [
                        'category' => 'Categories',
                        'statistic' => $categories_data[0],
                        'footer' => 'Least popular category'
                    ],
                ]
            ];

            return $data;
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
            $min = 0;
            $max = 0;

            foreach($categories as $category){
                $temp = $category->get_expenses();
                if($min == 0){
                    $min = $temp;
                    $accounts[0] = $category->name;
                }
                
                if($temp > $max){
                    $max = $temp;
                    $accounts[1] = $category->name;
                }
                elseif($temp < $min){
                    $min = $temp;
                    $accounts[0] = $category->name;
                }
            }

            return $accounts;
        }

    }
