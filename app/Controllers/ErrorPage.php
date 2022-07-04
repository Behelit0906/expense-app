<?php
    namespace app\Controllers;
    use app\Classes\Controller;

    class ErrorPage extends Controller{


        public function index(){
            
            $this->render('404/index',['currentPage' => 'error404']);

        }

    }