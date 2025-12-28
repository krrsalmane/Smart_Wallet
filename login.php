<?php
require_once 'entity/User.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    
    $user = new User("", $_POST['email'], $_POST['password']);

    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($user->login($email, $password)) {
        echo "<div style='color:green; text-align:center;'>Welcome, " . $_SESSION['user_name'] . "</div>";
         header("Location: index.php");
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <div class="text-center mb-8">
            <p class="text-gray-500">Welcome back! Please login.</p>
            
        </div>

        <form action="login.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md outline-none">
            </div>
            <button type="submit" name="login" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 font-semibold">Sign In</button>
            <p class="text-center text-sm text-gray-600">
                Don't have an account? <a href="register.php" class="text-blue-600 hover:underline">Register</a>
            </p>
        </form>
    </div>

</body>
</html>