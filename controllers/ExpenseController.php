<?php
session_start();

require_once __DIR__ . '/../models/Expense.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$expense = new Expense();
$user_id = $_SESSION['user_id'];

// Determine action
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category_id = $_POST['category_id'] ?? '';
            $amount = $_POST['amount'] ?? '';
            $description = $_POST['description'] ?? '';
            $expense_date = $_POST['expense_date'] ?? '';

            $result = $expense->create($user_id, $category_id, $amount, $description, $expense_date);

            if ($result['success']) {
                $_SESSION['success_message'] = 'Expense added successfully!';
                header('Location: ../index.php');
            } else {
                $_SESSION['error_message'] = implode(', ', $result['errors'] ?? ['Failed to add expense']);
                header('Location: ../index.php');
            }
            exit;
        }
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $category_id = $_POST['category_id'] ?? '';
            $amount = $_POST['amount'] ?? '';
            $description = $_POST['description'] ?? '';
            $expense_date = $_POST['expense_date'] ?? '';

            $result = $expense->update($id, $user_id, $category_id, $amount, $description, $expense_date);

            if ($result['success']) {
                $_SESSION['success_message'] = 'Expense updated successfully!';
            } else {
                $_SESSION['error_message'] = 'Failed to update expense';
            }
            header('Location: ../index.php');
            exit;
        }
        break;

    case 'delete':
        $id = $_GET['id'] ?? '';
        
        if ($expense->delete($id, $user_id)) {
            $_SESSION['success_message'] = 'Expense deleted successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to delete expense';
        }
        header('Location: ../index.php');
        exit;

    default:
        header('Location: ../index.php');
        exit;
}