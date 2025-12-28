<?php
require_once __DIR__ . "/../config/sql.php";

class Categorie
{
    private $db;
    private int $id;
    private string $name;
    private string $type;

    public function __construct($name = "", $type = "", $id = 0)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        
        $std = new Database();
        $this->db = $std->getConnection();
    }

   
    public static function getAllByType(string $type): array
    {
        $std = new Database();
        $db = $std->getConnection();
        
        $sql = "SELECT * FROM categories WHERE type = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$type]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getType(): string { return $this->type; }
    public function setType(string $type): void { $this->type = $type; }
}