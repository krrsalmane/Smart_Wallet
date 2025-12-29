<?php
require_once "models/User.php"; // Correct path based on your structure

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Create user object (Name is empty because it's not needed for login)
    $user = new User("", $_POST['email'], $_POST['password']);

    if ($user->login($_POST['email'], $_POST['password'])) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | FinSphere</title>
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
            <h1 class="text-3xl font-extrabold text-white mb-2">Welcome Back</h1>
            <p class="text-slate-400 text-sm">Login to your <span class="text-emerald-400 font-bold">IncomeTracker</span> account</p>
        </div>

        <?php if ($error): ?>
        <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl mb-6 text-sm">
            <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-6">
            <div>
                <label class="text-xs font-bold uppercase text-slate-500 ml-1">Email Address</label>
                <input type="email" name="email" required 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                       class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500 text-white mt-1" 
                       placeholder="your@email.com">
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="text-xs font-bold uppercase text-slate-500 ml-1">Password</label>
                </div>
                <input type="password" name="password" required 
                       class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500 text-white mt-1" 
                       placeholder="••••••••">
            </div>

            <button type="submit" name="login"
                    class="w-full bg-gradient-to-r from-emerald-600 to-emerald-500 text-white font-black py-3 rounded-xl hover:from-emerald-500 hover:to-emerald-400 uppercase tracking-widest text-sm transition shadow-lg">
                Sign In
            </button>
        </form>

        <p class="text-center text-slate-400 text-sm mt-8">
            New to the platform? 
            <a href="register.php" class="text-emerald-400 hover:text-emerald-300 font-bold transition">Create an account</a>
        </p>
    </div>

</body>
</html>