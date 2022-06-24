<?php
    namespace app\Classes;
    use app\Classes\Database;

    class Model {
        protected $pdo;

        public function __construct()
        {
            $this->pdo = Database::connect();
        }

    }