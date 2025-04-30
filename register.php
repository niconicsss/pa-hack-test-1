<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles/register.css">
</head>
<body>
    <form action="actions/register_action.php" method="POST">
        <h2>Register</h2>
        <input type="text" name="name" placeholder="Full Name (Last Name, First Name, M.I)" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
        <button type="submit">Register</button>
        <a href="login.php">Already have an account? Login</a>
    </form>
</body>
</html>
