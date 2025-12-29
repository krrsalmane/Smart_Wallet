<?php
// 1. Prevent Caching and Start Session
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "controllers/IncomeController.php";
require_once "controllers/ExpenseController.php";
require_once "models/Categorie.php";

// 2. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// 3. Initialize Controllers
$incomeCtrl = new IncomeController();
$expenseCtrl = new ExpenseController();
$incomeModel = new Income(0, "", "", $user_id, 0);
$expenseModel = new Expense(0, "", "", $user_id, 0);

// --- ACTION LOGIC: DELETE ---
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = $_GET['id'];
    if ($_GET['type'] === 'income') {
        $incomeCtrl->deleteIncome($id);
    } else {
        $expenseCtrl->deleteExpense($id);
    }
}

// --- ACTION LOGIC: UPDATE ---
if (isset($_POST['update_transaction'])) {
    if ($_POST['transaction_type'] === 'income') {
        $incomeCtrl->updateIncome($_POST['id']);
    } else {
        $expenseCtrl->updateExpense($_POST['id']);
    }
}

// --- ACTION LOGIC: ADD ---
if (isset($_POST['add_income'])) { $incomeCtrl->addIncome(); }
if (isset($_POST['add_expense'])) { $expenseCtrl->addExpense(); }

// --- ACTION LOGIC: FETCH DATA FOR EDIT FORM ---
$editData = null;
$editType = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit') {
    $editType = $_GET['type'];
    if ($editType === 'income') {
        $editData = $incomeModel->getById($_GET['id']);
    } else {
        $editData = $expenseModel->getById($_GET['id']);
    }
}

// 4. Fetch Dashboard Data
$totalIncome = $incomeModel->incomeTotal();
$totalExpense = $expenseModel->expenseTotal();
$balance = $totalIncome - $totalExpense;

$incomes = $incomeModel->getAll();
$expenses = $expenseModel->getAll();
$incomeCats = Categorie::getAllByType('income');
$expenseCats = Categorie::getAllByType('expense');

$transactions = array_merge($incomes, $expenses);
usort($transactions, function ($a, $b) {
    return strtotime($b['income_date'] ?? $b['expense_date']) - strtotime($a['income_date'] ?? $a['expense_date']);
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | FinSphere</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: radial-gradient(circle at top left, #1e293b, #0f172a); color: white; }
        .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .modal { display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.8); align-items: center; justify-content: center; z-index: 50; }
        .modal.active { display: flex; }
    </style>
</head>
<body class="min-h-screen p-4 md:p-8">

    <div class="max-w-6xl mx-auto flex justify-between items-center mb-10">
        <h1 class="text-2xl font-extrabold tracking-tight">FinSphere<span class="text-emerald-400">.</span></h1>
        <a href="logout.php" class="glass px-6 py-2 rounded-xl text-sm font-bold hover:bg-red-500/20 transition">Logout</a>
    </div>

    <?php if ($editData): ?>
    <div class="max-w-6xl mx-auto mb-10">
        <div class="glass p-8 rounded-3xl border-emerald-500/50 border">
            <h2 class="text-xl font-bold mb-6 text-emerald-400">Edit <?= ucfirst($editType) ?></h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                <input type="hidden" name="transaction_type" value="<?= $editType ?>">
                <input type="number" step="0.01" name="amount" value="<?= $editData['amount'] ?>" required class="bg-slate-800 p-3 rounded-xl border border-white/10 text-white outline-none">
                <input type="text" name="description" value="<?= $editData['description'] ?>" required class="bg-slate-800 p-3 rounded-xl border border-white/10 text-white outline-none">
                <input type="date" name="date" value="<?= $editData['income_date'] ?? $editData['expense_date'] ?>" required class="bg-slate-800 p-3 rounded-xl border border-white/10 text-white outline-none">
                <select name="category_id" class="bg-slate-800 p-3 rounded-xl border border-white/10 text-white outline-none">
                    <?php $cats = ($editType == 'income') ? $incomeCats : $expenseCats; ?>
                    <?php foreach($cats as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $c['id'] == $editData['category_id'] ? 'selected' : '' ?>><?= $c['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="flex gap-2">
                    <button type="submit" name="update_transaction" class="flex-1 bg-emerald-500 text-slate-900 font-bold rounded-xl">Update</button>
                    <a href="index.php" class="flex-1 bg-slate-700 text-white font-bold rounded-xl flex items-center justify-center">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 space-y-6">
            <div class="glass p-8 rounded-3xl">
                <p class="text-slate-400 text-xs font-bold uppercase mb-1">Total Balance</p>
                <h2 class="text-4xl font-black">$<?php echo number_format($balance, 2); ?></h2>
                <div class="mt-6 grid grid-cols-2 gap-4 border-t border-white/5 pt-6">
                    <div><p class="text-emerald-400 font-bold">+$<?php echo number_format($totalIncome, 2); ?></p></div>
                    <div><p class="text-rose-400 font-bold">-$<?php echo number_format($totalExpense, 2); ?></p></div>
                </div>
            </div>
            <div class="glass p-6 rounded-3xl space-y-3">
                <button onclick="toggleModal('incomeModal')" class="w-full bg-emerald-500 text-slate-900 font-bold py-3 rounded-xl">Add Income</button>
                <button onclick="toggleModal('expenseModal')" class="w-full border border-white/10 text-white font-bold py-3 rounded-xl">Add Expense</button>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="glass rounded-3xl overflow-hidden">
                <table class="w-full text-left">
                    <thead class="text-slate-500 text-[10px] uppercase"><tr class="border-b border-white/5"><th class="px-6 py-4">Date</th><th class="px-6 py-4">Description</th><th class="px-6 py-4">Category</th><th class="px-6 py-4 text-right">Amount</th><th class="px-6 py-4 text-center">Actions</th></tr></thead>
                    <tbody class="divide-y divide-white/5">
                        <?php foreach (array_slice($transactions, 0, 10) as $t): 
                            $type = isset($t['income_date']) ? 'income' : 'expense';
                        ?>
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-6 py-4 text-sm text-slate-400"><?= date('M d, Y', strtotime($t['income_date'] ?? $t['expense_date'])); ?></td>
                            <td class="px-6 py-4 text-sm font-medium"><?= htmlspecialchars($t['description']); ?></td>
                            <td class="px-6 py-4"><span class="text-[10px] font-bold px-2 py-1 rounded bg-slate-800"><?= htmlspecialchars($t['category_name']); ?></span></td>
                            <td class="px-6 py-4 text-right font-bold <?= $type === 'income' ? 'text-emerald-400' : 'text-rose-400'; ?>"><?= $type === 'income' ? '+' : '-'; ?> $<?= number_format($t['amount'], 2); ?></td>
                            <td class="px-6 py-4 text-center space-x-3">
                                <a href="index.php?action=edit&type=<?= $type ?>&id=<?= $t['id'] ?>" class="text-emerald-400 hover:underline">Edit</a>
                                <a href="index.php?action=delete&type=<?= $type ?>&id=<?= $t['id'] ?>" class="text-rose-400 hover:underline">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="incomeModal" class="modal">
        <div class="glass p-8 rounded-3xl w-full max-w-md">
            <h2 class="text-xl font-bold mb-6 text-emerald-400">Add Income</h2>
            <form method="POST" class="space-y-4">
                <input type="number" step="0.01" name="amount" placeholder="Amount" required class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none">
                <input type="text" name="description" placeholder="Description" required class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none">
                <input type="date" name="date" required class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none">
                <select name="category_id" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none">
                    <?php foreach ($incomeCats as $c): ?><option value="<?= $c['id'] ?>"><?= $c['name'] ?></option><?php endforeach; ?>
                </select>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal('incomeModal')" class="flex-1 text-slate-400 py-3">Cancel</button>
                    <button type="submit" name="add_income" class="flex-1 bg-emerald-500 text-slate-900 font-bold rounded-xl">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div id="expenseModal" class="modal">
        <div class="glass p-8 rounded-3xl w-full max-w-md">
            <h2 class="text-xl font-bold mb-6 text-rose-400">Add Expense</h2>
            <form method="POST" class="space-y-4">
                <input type="number" step="0.01" name="amount" placeholder="Amount" required class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none">
                <input type="text" name="description" placeholder="Description" required class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none">
                <input type="date" name="date" required class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none">
                <select name="category_id" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none">
                    <?php foreach ($expenseCats as $c): ?><option value="<?= $c['id'] ?>"><?= $c['name'] ?></option><?php endforeach; ?>
                </select>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal('expenseModal')" class="flex-1 text-slate-400 py-3">Cancel</button>
                    <button type="submit" name="add_expense" class="flex-1 bg-rose-500 text-white font-bold rounded-xl">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            document.getElementById(id).classList.toggle('active');
        }
    </script>
</body>
</html>