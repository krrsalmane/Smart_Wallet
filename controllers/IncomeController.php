<?php
session_start();

require_once __DIR__ . '/../models/Income.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$income = new Income();
$user_id = $_SESSION['user_id'];

// Determine action
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category_id = $_POST['category_id'] ?? '';
            $amount = $_POST['amount'] ?? '';
            $description = $_POST['description'] ?? '';
            $income_date = $_POST['income_date'] ?? '';

            $result = $income->create($user_id, $category_id, $amount, $description, $income_date);

            if ($result['success']) {
                $_SESSION['success_message'] = 'Income added successfully!';
                header('Location: ../index.php');
            } else {
                $_SESSION['error_message'] = implode(', ', $result['errors'] ?? ['Failed to add income']);
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
            $income_date = $_POST['income_date'] ?? '';

            $result = $income->update($id, $user_id, $category_id, $amount, $description, $income_date);

            if ($result['success']) {
                $_SESSION['success_message'] = 'Income updated successfully!';
            } else {
                $_SESSION['error_message'] = 'Failed to update income';
            }
            header('Location: ../index.php');
            exit;
        }
        break;

    case 'delete':
        $id = $_GET['id'] ?? '';
        
        if ($income->delete($id, $user_id)) {
            $_SESSION['success_message'] = 'Income deleted successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to delete income';
        }
        header('Location: ../index.php');
        exit;

    default:
        header('Location: ../index.php');
        exit;
}