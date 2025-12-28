<?php
require_once 'operation.php';

class Income extends operation {

    private $user_id;
    private $category_id;

    public function __construct($amount, $description, $date, $user_id, $category_id)
    {
        parent::__construct($amount, $description, $date);
        $this->user_id = $user_id;
        $this->category_id = $category_id;
    }

    public function create(): bool
    {
        $sql = "INSERT INTO incomes (amount, description, income_date, user_id, category_id) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $this->amount,      
            $this->description,
            $this->my_date,    
            $this->user_id,    
            $this->category_id 
        ]);
    }

    public function update($id): bool
    {
        $sql = "UPDATE incomes SET amount = ?, description = ?, income_date = ?, category_id = ? 
                WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $this->amount,
            $this->description,
            $this->my_date,
            $this->category_id,
            $id,
            $this->user_id
        ]);
    }

    public function delete($id): bool
    {
        $sql = "DELETE FROM incomes WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id, $this->user_id]);
    }

    public function incomeTotal()
    {
        $sql = "SELECT SUM(amount) as total FROM incomes WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getByCategory($category_id)
    {
        $sql = "SELECT i.*, c.name as category_name FROM incomes i 
                JOIN categories c ON i.category_id = c.id 
                WHERE i.category_id = ? AND i.user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$category_id, $this->user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $sql = "SELECT i.*, c.name as category_name FROM incomes i 
                JOIN categories c ON i.category_id = c.id 
                WHERE i.user_id = ? ORDER BY i.income_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM incomes WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id, $this->user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>