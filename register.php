<?php
session_start();

require_once 'models/User.php';

// Redirect if already logged in
if (User::isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        $user = new User();
        $result = $user->register($full_name, $email, $password);

        if ($result['success']) {
            $success = 'Registration successful! You can now login.';
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | FinSphere</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: radial-gradient(circle at top left, #1e293b, #0f172a); 
        }
        .glass { 
            background: rgba(30, 41, 59, 0.7); 
            backdrop-filter: blur(12px); 
            border: 1px solid rgba(255, 255, 255, 0.1); 
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    
    <div class="glass p-10 rounded-3xl w-full max-w-md shadow-2xl">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-white mb-2">Create Account</h1>
            <p class="text-slate-400 text-sm">Join <span class="text-emerald-400 font-bold">FinSphere</span> today</p>
        </div>

        <?php if ($error): ?>
        <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl mb-6 text-sm">
            <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/50 text-emerald-400 px-4 py-3 rounded-xl mb-6 text-sm">
            <strong>Success!</strong> <?php echo htmlspecialchars($success); ?>
            <a href="login.php" class="underline font-bold ml-2">Login now</a>
        </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <div>
                <label class="text-xs font-bold uppercase text-slate-500 ml-1">Full Name</label>
                <input type="text" name="full_name" required 
                       value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>"
                       class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500 text-white mt-1" 
                       placeholder="John Doe">
            </div>

            <div>
                <label class="text-xs font-bold uppercase text-slate-500 ml-1">Email Address</label>
                <input type="email" name="email" required 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                       class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500 text-white mt-1" 
                       placeholder="your@email.com">
            </div>

            <div>
                <label class="text-xs font-bold uppercase text-slate-500 ml-1">Password</label>
                <input type="password" name="password" required 
                       class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500 text-white mt-1" 
                       placeholder="••••••••">
                <p class="text-xs text-slate-500 mt-1 ml-1">Minimum 6 characters</p>
            </div>

            <div>
                <label class="text-xs font-bold uppercase text-slate-500 ml-1">Confirm Password</label>
                <input type="password" name="confirm_password" required 
                       class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500 text-white mt-1" 
                       placeholder="••••••••">
            </div>

            <button type="submit" 
                    class="w-full bg-gradient-to-r from-emerald-600 to-emerald-500 text-white font-black py-3 rounded-xl hover:from-emerald-500 hover:to-emerald-400 uppercase tracking-widest text-sm transition shadow-lg">
                Create Account
            </button>
        </form>

        <p class="text-center text-slate-400 text-sm mt-6">
            Already have an account? 
            <a href="login.php" class="text-emerald-400 hover:text-emerald-300 font-bold transition">Login here</a>
        </p>
    </div>

</body>
</html>