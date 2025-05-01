<?php 
// If user is already logged in, redirect to dashboard
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Get Started</title>
    <link rel="stylesheet" href="styles/get_started.css">
    <!-- Google Font links -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Poppins:wght@400;600&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">


</head>
<body>
    <div>
        <!-- Logo -->
        <img src="image/boom.png" alt="SkillCargo Logo" class="logo">

        <!-- Heading and Catchphrase -->
        <h2>Welcome to SkillCargo</h2>
        <p>"From Learning to Earning SkillCargo’s Got You Covered!"</p> <!-- Catchphrase here -->

        <!-- Get Started Button -->
        <div>
            <a href="login.php" class="get-started-btn">Get Started</a>
        </div>
    </div>
</body>
</html>
