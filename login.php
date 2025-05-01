<?php 
session_start();
include 'config/db.php'; 


if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php'); // or wherever you want to redirect
    exit;
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="actions/login_action.php" method="POST">
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="Email" required>

            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Login</button>
        </form>
        <div class="bottom-link">
            <a href="register.php">Create an account</a>
        </div>
    </div>
</body>
</html>