<?php 
    class Database {
        private $host = "187.45.196.191";
        private $database_name = "ms_dourados";
        private $username = "ms_dourados";
        private $password = "M@ndruva135";

        public $conn;

        public function getConnection(){
            $this->conn = null;

            try{
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");
            }catch(PDOException $exception){
                echo "Falha na conexão com database: " . $exception->getMessage();
            }

            return $this->conn;
        }
    }  
?>