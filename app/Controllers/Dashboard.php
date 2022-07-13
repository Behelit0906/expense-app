<?php
    namespace app\Controllers;
    use app\Classes\Controller;
    use app\Models\User;
    use app\Models\Expense;
    use app\Models\Category;
    use app\Classes\Validator;

    class Dashboard extends Controller{

        private $user;
        private $expense;

        public function __construct()
        {
            parent::__construct();
             
            $role = $this->checkRole($_SESSION['user_id']);
            if($role == 'admin'){
                $this->redirect('/admin-panel');
            }
            $this->user = new User;
            $this->expense = new Expense;
            $this->user->find($_SESSION['user_id']);
        }

        public function index(){
            
            $this->render('dashboard/index',['currentPage' => 'dashboard']);

        }

        public function save(){
            
            $temp = $this->prepareValidations([
                'name' => ['required','min:3','max:15'],
                'amount' => ['required','numeric'],
                'date' => ['required','date'],
                'categoryId' => ['required','numeric']   
            ]);

            $amount = $_POST['amount'];

            $validator = new Validator;
            $errorMessages = $validator->validate($temp[0], $temp[1]);

            if( $amount  < 0){
                array_push($errorMessages, 'The amount must be major than zero');
            }

            if(count($errorMessages) > 0){
                $this->json_response(400,['messages' => $errorMessages]);
            }

            $name = $_POST['name'];
            $date = $_POST['date'];
            $category_id = $_POST['categoryId'];
            
            $this->expense->name = $name;
            $this->expense->amount = $amount;
            $this->expense->date = $date;
            $this->expense->user_id = $this->user->id;
            $this->expense->category_id = $category_id;

            $this->expense->store();

            $this->json_response(201,['messages' => 'Expense recorded']);
        }

        public function getData(){
            $general_balance = $this->balanceMonth();
            $category = new Category;
            $categories = $category->get_all();


            $data['user-data'] =[
                'user_name' => $this->user->name,
                'photo' => $this->user->photo,
                'general_balance' => $general_balance,
                'budget' => intval($this->user->budget),
                'residual_budget' => $this->user->budget - $general_balance,
                'biggets_expense' => $this->biggestExpenseThisMonth()
            ];

            $data['categories'] = $categories;
            $data['category_transactions'] = $this->transactionsByCategorythisMonth();
            $data['recent_expenses'] = $this->recentExpenses();

            // $data = [
            //     'user_name' => $this->user->name,
            //     'photo' => $this->user->photo,
            //     'general_balance' => $general_balance,
            //     'budget' => intval($this->user->budget),
            //     'residual_budget' => $this->user->budget - $general_balance,
            //     'biggets_expense' => $this->biggestExpenseThisMonth(),
            //     'categories' => $categories,
            //     'category_transactions' => $this->transactionsByCategorythisMonth(),
            //     'recent_expenses' => $this->recentExpenses(),
            // ];

            // header('Content-Type: application/json');
            // echo json_encode($data);
            // exit();   
            $this->json_response('200',$data);
            
        }

        private function balanceMonth(){
            $expenses = $this->expense->getExpensesThisMonth($this->user->id, date('m'));

            $balance = 0;

            foreach($expenses as $expense){
                $balance += $expense->amount;
            }

            return $balance;
        }

        private function recentExpenses(){;
            return $this->expense->limit($this->user->id,0, 5); 
        }

        private function biggestExpenseThisMonth(){
            $month = date('m');
            return $this->expense->biggestExpenseThisMonth($this->user->id, $month);
        }

        private function transactionsByCategorythisMonth(){
           
            return $this->transactionsDataThisMonth();
            
        }


        public function chartData(){
            $data = $this->transactionsDataThisMonth();

            header('Content-Type: application/json');
            echo json_encode($data);
            exit();
        }

        private function transactionsDataThisMonth(){
            $category = new Category;
            $categories = $category->get_all();

            $items = [];

            foreach($categories as $category){
                $amount = 0;
                $transactions = $category->getExpensesByMonth($this->user->id, date('m'));

                foreach($transactions as $transaction){
                    $amount += $transaction->amount;
                }
                

                array_push($items,[
                    'name' => $category->name,
                    'color' => $category->color,
                    'transactions' => count($transactions),
                    'amount' => $amount
                ]);
            }

            return $items;

            
        }


    }