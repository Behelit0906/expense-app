<?php
    namespace app\Controllers;
    use app\Classes\Controller;
    use app\Models\Expense as expenseModel;
    use app\Models\User;
    use app\Classes\Validator;
    use app\Models\Category;

    class Expense extends Controller{

        private $user;
        private $expense;
        private $role;
        private $category;

        public function __construct()
        {
            parent::__construct();
            $this->user = new User;
            $this->user->find($_SESSION['user_id']);
            $this->role = $this->user->rol;

            if($this->role == 'admin'){
                $this->redirect('/admin-panel');
            }

            $this->expense = new expenseModel;
            $this->category = new Category;
        }

        public function index(){
            $this->render('expenses/index',['role' => $this->role]);
        }


        public function expensesData($pointer, $amount){
            $data = array();
            $expenses = $this->expense->limit($_SESSION['user_id'], $pointer, $amount);
            $totalExpenses = count($this->user->get_expenses());

            $data['user-data'] = [
                'user_name' => $this->user->name,
                'photo' => $this->user->photo,
            ];      
            $data['expenses'] = $expenses;
            $data['categories'] = $this->categories();
            $data['total'] = $totalExpenses;

            $this->json_response('200', $data);
        }


        private function categories(){
            $expenses = $this->user->get_expenses();
            $categories = $this->category->get_all();
            
            // $categories = [];
            // foreach($expenses as $expense){
            //     $category = $expense->belongToCategory();
            //     if($category){
            //         if(!array_key_exists($category->id, $categories)){
            //             $categories[$category->id] = $category->name; 
            //         }
            //     } 
            // }

            return $categories;
        }

        public function filteredExpensesData($category_id, $pointer, $amount){
            $data = [];
            $expenses = $this->expense->limitAndFiltered($this->user->id, $category_id, $pointer, $amount);
            $data['expenses'] = $expenses;
            $data['categories'] = [$this->category->find($category_id)];
            $data['total'] = $this->expense->totalExpensesByCategory($this->user->id, $category_id);

            $this->json_response('200', $data);
        }

        public function delete(){

            $temp = $this->prepareValidations([
                'id' => ['required','numeric'],
            ]);


            $validator = new Validator;
            $errorMessages = $validator->validate($temp[0], $temp[1]);

            if(count($errorMessages) > 0){
                $this->json_response(400, ['messages' => $errorMessages]);
            }

            $id = $_POST['id'];
            $isOk = $this->expense->delete($id);

            if($isOk){
                $this->json_response('201',['messages' => 'Expense deleted']);
            }
            else{
                $this->json_response('400',['messages' => 'An unknown error occurred, try again later']);
            }     

        }


    }