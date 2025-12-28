<?php
require_once __DIR__ . "/../config/Database.php";

class User {
    private $db;
    private $full_Name;
    private $email;
    private $password;

    public function __construct($full_Name = null, $email = null, $password = null) {
        $this->full_Name = $full_Name;
        $this->email = $email;
        $this->password = $password; 
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function setFullName($name) { $this->full_Name = $name; }
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = $password; }

    public function register(): array {
        $errors = [];
        if (empty($this->full_Name) || empty($this->email) || empty($this->password)) {
            return ["All fields are required"];
        }

        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$this->email]);
        if ($stmt->rowCount() > 0) return ["Email already exists"];

        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$this->full_Name, $this->email, $hashedPassword]) ? [] : ["Something went wrong"];
    }

    public function login($email, $password): bool {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            return true;
        }
        return false;
    }
}