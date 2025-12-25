<?php
require __DIR__ . "/../config/sql.php";

class User

{

    private $db;
    private $full_Name;
    private $email;
    private $password;

    public function __construct( $full_Name,  $email,  $password){
        $this->full_Name = $full_Name;
        $this->email = $email;
        $this->password = $password;
        $std = new Database();
        $this->db = $std->getConnection();
    }

    public function setFullName(string $fullName): void
    {
        $this->full_Name = $fullName;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

     public function register(): array {
        $errors = [];

        if (empty($this->full_Name) || empty($this->email) || empty($this->password)) {
            $errors[] = "you should all the fields";
            return $errors;
        }

        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$this->email]);
        if ($stmt->rowCount() > 0) {
            $errors[] = " email already exist";
            return $errors;
        }

        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$this->full_Name, $this->email, $hashedPassword])) {
            return []; 
        } else {
            $errors[] = "Erreur something went wrong";
            return $errors;
        }
    }

    public function login(string $email, string $password): bool {
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
