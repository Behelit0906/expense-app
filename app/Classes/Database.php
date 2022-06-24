<?php
    namespace app\Classes;
    require('app/Config/Config.php');
    use PDO;
    use PDOException;

    class Database{

        public static function connect(){
            $dns = 'mysql:dbname='.DBNAME.';host='.HOST;
            
            try {
                $dbh = new PDO($dns, USER, PASSWORD);
                return $dbh;

            } catch (PDOException $e) {
                throw $e;
            } 
        }

    }