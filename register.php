<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
<h2>Register</h2>
<form action="actions/register_action.php" method="POST">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="text" name="business" placeholder="Business Name" required><br>
    <input type="number" name="radius" placeholder="Radius (km)" required><br>
    <button type="submit">Register</button>
</form>
<a href="index.php">Already have an account? Login</a>
</body>
</html>
