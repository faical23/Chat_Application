<?php


class DB{
    private $dbhost ='localhost' ;
    private $username = 'root';
    private $password = '';
    private $dsn ;
    protected $dbh;

    public function __construct($dbname){
            $this->dsn = "mysql:host={$this->dbhost};dbname={$dbname}";
            try {
            $this->dbh = new PDO($this->dsn, $this->username, $this->password ,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            } catch(Exception $e) {
            echo "Connection failed: " . $e->getMessage();
            }
    }
}




?>