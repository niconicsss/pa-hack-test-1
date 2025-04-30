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
       * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  body {
    font-family: 'Segoe UI', sans-serif;
    background-color: var(--bg-light);
    color: var(--text-color);
  }
  
  .dashboard {
    display: flex;
    min-height: 100vh;
  }
  
  /* Sidebar */
  .sidebar {
    background-color: var(--accent-dark);
    color: white;
    width: 220px;
    padding: 20px;
  }
  
  .sidebar h2 {
    font-size: 1.5rem;
    margin-bottom: 30px;
    color: var(--primary-color);
  }
  
  .sidebar nav a {
    display: block;
    color: white;
    text-decoration: none;
    margin-bottom: 15px;
    font-size: 1rem;
    padding: 8px;
    border-radius: 5px;
    transition: background-color 0.2s;
  }
  
  .sidebar nav a:hover {
    background-color: var(--primary-color);
  }
  
  /* Main content */
  .main {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
  }
  
  /* Header */
  .topbar {
    background-color: var(--card-bg);
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #d6c7ac;
  }
  
  .topbar h1 {
    font-size: 1.8rem;
  }
  
  .user-profile {
    font-weight: bold;
    color: var(--accent-dark);
  }
  
  /* Content */
  .content {
    padding: 30px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
  }
  
  .card {
    background-color: var(--card-bg);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    font-size: 1.1rem;
    font-weight: 500;
    border-left: 5px solid var(--primary-color);
    transition: transform 0.2s;
  }
  
  .card:hover {
    transform: scale(1.02);
    border-left-color: var(--accent-dark);
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
</html>gitpl