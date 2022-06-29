<?php
    namespace app\Controllers;
    use app\Classes\Controller;
    use app\Models\User;

    class Dashboard extends Controller{

        private $allowedRole;

        public function __construct()
        {
            parent::__construct();
            $this->allowedRole = 'user';
             
            $role = $this->checkRole($_SESSION['user_id']);
            if($role != 'user'){
                $this->redirect($_SERVER['HTTP_REFERER']);
                exit();
            }

        }

        public function index(){
            $user = new User;
            $user->find($_SESSION['user_id']);
            $expenses = $user->get_expenses();
            $categories = [];

            foreach($expenses as $expense){
                $category = $expense->belongToCategory();
                if($category){
                    if(!array_key_exists($category->name,$categories)){
                        $categories[$category->name] = $category->color;
                    }
                }
            }

            $data =[
                'user' => $user,
                'expenses' => $expenses,
                'categories' => $categories,
                'currentPage' => 'dashboard'
            ];
            $this->render('dashboard/index', $data);
        }








    }