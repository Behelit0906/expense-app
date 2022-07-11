<?php
    namespace app\Controllers;
    use app\Classes\Controller;
    use app\Models\Expense as expenseModel;
    use app\Models\User;

    class Expense extends Controller{

        private $user;
        private $expense;

        public function __construct()
        {
            parent::__construct();
            $this->user = new User;
            $this->user->find($_SESSION['user_id']);
            $this->expense = new expenseModel;
        }

        public function index(){
            $this->render('expenses/index',['currentPage' => 'expenses']);
        }


        public function expensesData($pointer, $amount){
            $data = array();
            $expenses = $this->expense->limit($_SESSION['user_id'], $pointer, $amount);
            $totalExpenses = count($this->user->get_expenses());

            $data['expenses'] = $expenses;
            $data['total'] = $totalExpenses;

            $this->json_response('200', $data);
        }

        

        private function categories(){
            $categories = [];
            $expenses = $this->user->get_expenses();
            
            $temp = [];
            foreach($expenses as $expense){
                $category = $expense->belongToCategory();
                if(!array_key_exists($category->name, $temp)){
                    $temp[$category->name] = $category; 
                }
            }

            foreach($temp as $key => $value){
                array_push($categories, $value);
            }

            return $categories;
        }

        public function filteredExpensesData($category_id, $pointer, $amount){
            $data = [];
            $expenses = $this->expense->limitAndFiltered($this->user->id, $category_id, $pointer, $amount);
            $data['expenses'] = $expenses;
            $data['total'] = $this->expense->totalExpensesByCategory($this->user->id, $category_id);

            $this->json_response('200', $data);
        }


    }