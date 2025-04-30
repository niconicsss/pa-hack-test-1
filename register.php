<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles/register.css">
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form action="actions/register_action.php" method="POST">
            <input type="text" name="name" placeholder="Full Name (Last Name, First Name, M.I)" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
        <div class="bottom-link">
            <a href="login.php">Already have an account? <strong>Login</strong></a>
        </div>
    </div>
</body>
</html>
