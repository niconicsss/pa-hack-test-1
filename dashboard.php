<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Redirect if not logged in
    exit;
}

include('config/db.php');
$user_id = $_SESSION['user_id'];

// Fetch user info
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the external CSS file -->
</head>
<body>

<div class="header">
    <a href="includes/logout.php" style="color: #007BFF; font-weight: bold; text-decoration: none;">Logout</a>
</div>

<div class="dashboard">
    <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>

    <h2>Your Business: <?php echo htmlspecialchars($user['business']); ?></h2>
    <p>Radius: <?php echo htmlspecialchars($user['radius']); ?> km</p>

    <!-- Tabs -->
    <div class
