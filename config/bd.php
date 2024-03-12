<?php

class DB {
    private $host = "localhost";
    private $port = "3307";
    private $user = "root";
    private $password = "";
    private $dbname = "bd_customers";

    public function connect(){
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8";
        $pdo = new PDO($dsn, $this->user, $this->password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
}