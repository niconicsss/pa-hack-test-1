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
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Verdana, sans-serif;
            background-color: #f4f4f4;
        }

        .header {
            position: fixed;
            top: 10px;
            right: 20px;
            font-weight: bold;
        }

        .dashboard {
            margin-top: 50px;
            padding: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #333;
        }

        p {
            color: #555;
        }

        .tabs {
            margin: 20px 0;
            display: flex;
            justify-content: space-around;
        }

        .tabs a {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .tabs a:hover {
            background-color: #0056b3;
        }

        .content {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="header">
    <a href="index.php" style="color: #007BFF; font-weight: bold; text-decoration: none;">Logout</a>
</div>

<div class="dashboard">
    <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>

    <h2>Your Business: <?php echo htmlspecialchars($user['business']); ?></h2>
    <p>Radius: <?php echo htmlspecialchars($user['radius']); ?> km</p>

    <!-- Tabs -->
    <div class="tabs">
        <a href="business/search.php">Search Nearby Businesses</a>
        <a href="orders/history.php">View Orders</a>
        <a href="orders/status.php">Track Orders</a>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- This section will display relevant information based on the clicked tab -->
    </div>
</div>

</body>
</html>