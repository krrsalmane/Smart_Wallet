<?php
require_once __DIR__ . '/../models/Income.php';

class IncomeController {

    public function addIncome() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $amount = $_POST['amount'];
            $description = $_POST['description'];
            $date = $_POST['date'];
            $category_id = $_POST['category_id'];
            $user_id = $_SESSION['user_id'];

            $income = new Income($amount, $description, $date, $user_id, $category_id);

            if ($income->create()) {
                header("Location: index.php?msg=added");
                exit();
            }
        }
    }

    public function deleteIncome($id) {
        $user_id = $_SESSION['user_id'];
        
        $income = new Income(0, "", "", $user_id, 0);
        
        if ($income->delete($id)) {
            header("Location: index.php?msg=deleted");
            exit();
        }
    }

    public function updateIncome($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $amount = $_POST['amount'];
            $description = $_POST['description'];
            $date = $_POST['date'];
            $category_id = $_POST['category_id'];
            $user_id = $_SESSION['user_id'];

            $income = new Income($amount, $description, $date, $user_id, $category_id);

            if ($income->update($id)) {
                header("Location: index.php?msg=updated");
                exit();
            }
        }
    }
}