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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Supply Dashboard</title>
  <style>
    :root {
      --primary-color: #D84040;
      --accent-dark: #A31D1D;
      --bg-light: #F8F2DE;
      --card-bg: #ECDCBF;
      --text-color: #2b2b2b;
    }

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

    .sidebar {
      background-color: var(--accent-dark);
      color: white;
      width: 240px;
      padding: 20px;
    }

    .sidebar h2 {
      font-size: 1.6rem;
      margin-bottom: 30px;
      color: var(--primary-color);
    }

    .sidebar nav a {
      display: block;
      color: white;
      text-decoration: none;
      margin-bottom: 15px;
      font-size: 1rem;
      padding: 10px;
      border-radius: 5px;
      transition: background-color 0.2s;
    }

    .sidebar nav a:hover {
      background-color: var(--primary-color);
    }

    .main {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .topbar {
      background-color: var(--card-bg);
      padding: 20px 30px;
      border-bottom: 1px solid #d6c7ac;
    }

    .topbar h1 {
      font-size: 1.8rem;
      margin-bottom: 5px;
    }

    .user-profile {
      font-size: 1rem;
      color: var(--accent-dark);
    }

    .content {
      padding: 30px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
    }

    .card {
      background-color: var(--card-bg);
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      font-size: 1.2rem;
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
  <div class="dashboard">
    <div class="sidebar">
      <h2>SupplyApp</h2>
      <nav>
        <a href="#">Search Nearby Businesses</a>
        <a href="#">View Orders</a>
        <a href="#">Track Orders</a>
        <a href="#">Logout</a>
      </nav>
    </div>

    <div class="main">
      <div class="topbar">
        <h1>Welcome, DAFA S FDFD</h1>
        <div class="user-profile">
          Your Business: <strong>pastries</strong> | Radius: <strong>200 km</strong>
        </div>
      </div>
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