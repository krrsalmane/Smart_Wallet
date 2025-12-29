<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Expense.php';

class ExpenseController {

    // Handles adding a new expense
    public function addExpense() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $amount = $_POST['amount'];
            $description = $_POST['description'];
            $date = $_POST['date']; // Make sure this matches your form input name
            $category_id = $_POST['category_id'];
            $user_id = $_SESSION['user_id'];

            $expense = new Expense($amount, $description, $date, $user_id, $category_id);

            if ($expense->create()) {
                header("Location: index.php?msg=expense_added");
                exit();
            }
        }
    }

    // Handles deleting an expense
    public function deleteExpense($id) {
        $user_id = $_SESSION['user_id'];
        
        // Dummy object to call the delete method
        $expense = new Expense(0, "", "", $user_id, 0);
        
        if ($expense->delete($id)) {
            header("Location: index.php?msg=expense_deleted");
            exit();
        }
    }

    // Handles updating an existing expense
    public function updateExpense($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $amount = $_POST['amount'];
            $description = $_POST['description'];
            $date = $_POST['date'];
            $category_id = $_POST['category_id'];
            $user_id = $_SESSION['user_id'];

            $expense = new Expense($amount, $description, $date, $user_id, $category_id);

            if ($expense->update($id)) {
                header("Location: index.php?msg=expense_updated");
                exit();
            }
        }
    }
}