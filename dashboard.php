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
</head>
<body>

    <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>

    <h2>Your Business: <?php echo htmlspecialchars($user['business']); ?></h2>
    <p>Radius: <?php echo htmlspecialchars($user['radius']); ?> km</p>

    <!-- Button to search nearby businesses -->
    <a href="business/search.php">Search Nearby Businesses</a>

    <!-- Link to view orders -->
    <a href="orders/history.php">View Orders</a>

    <!-- Link to track orders -->
    <a href="orders/status.php">Track Orders</a>

    <!-- Button to log out -->
    <a href="includes/logout.php">Logout</a>

</body>
</html>
