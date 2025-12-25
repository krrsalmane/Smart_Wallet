<?php
require 'entity/User.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Access</title>
</head>
<body>

<form action="register.php" method="POST">
    <h3>Register</h3>

    <input type="text" name="full_name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>

    <button type="submit" name="register">Sign Up</button>
</form>

</body>
</html>

<?php 
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $user = new User($_POST["full_name"], $_POST["email"], $_POST["password"]);
        $user->register();
    }


?>
