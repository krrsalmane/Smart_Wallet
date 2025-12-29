<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class Database {
    private $pdo;

    public function __construct() {
        $host = "localhost";
        $db   = "money_tracker";
        $user = "root";
        $pass = "root@123";

        try {
            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$db;charset=utf8",
                $user,
                $pass
            );
            $this->pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } catch (PDOException $e) {
            die("Database connection failed");
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
