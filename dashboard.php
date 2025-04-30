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
<<<<<<< HEAD
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
=======
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 500px; width: 100%; margin-top: 20px; }
        button { margin-top: 10px; }
    </style>
<<<<<<< HEAD
=======
>>>>>>> 904e0498eeb98e4bf2da645bfb4739658ee2a839
>>>>>>> 479a74546041a59f0faa7c5a4fffe65ec1d87277
>>>>>>> 2ecde85edb7a2b6b117e60bb59742d6b0a6e4e0e
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

<<<<<<< HEAD
=======
<<<<<<< HEAD
    <div class="main">
      <div class="topbar">
        <h1>Welcome, DAFA S FDFD</h1>
        <div class="user-profile">
          Your Business: <strong>pastries</strong> | Radius: <strong>200 km</strong>
        </div>
      </div>
=======
<div class="header">
    <a href="includes/logout.php" style="color: #007BFF; font-weight: bold; text-decoration: none;">Logout</a>
</div>

<div class="dashboard">
>>>>>>> 2ecde85edb7a2b6b117e60bb59742d6b0a6e4e0e
    <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
    <h2>Your Business: <?php echo htmlspecialchars($user['business']); ?></h2>
    <p>Radius: <?php echo htmlspecialchars($user['radius']); ?> km</p>

<<<<<<< HEAD
    <!-- Button to search nearby businesses -->
    <button onclick="loadMap()">View Nearby Businesses</button>
    <div id="map"></div>
=======
>>>>>>> 479a74546041a59f0faa7c5a4fffe65ec1d87277
    <!-- Tabs -->
<<<<<<< HEAD
    <div class
=======
    <div class="tabs">
        <a href="business/search.php">Search Nearby Businesses</a>
        <a href="orders/history.php">View Orders</a>
        <a href="orders/status.php">Track Orders</a>
    </div>
>>>>>>> 2ecde85edb7a2b6b117e60bb59742d6b0a6e4e0e

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    let map;

    function loadMap() {
        if (!navigator.geolocation) {
            alert("Geolocation not supported.");
            return;
        }

        navigator.geolocation.getCurrentPosition(function(pos) {
            const userLat = pos.coords.latitude;
            const userLng = pos.coords.longitude;

            if (!map) {
                map = L.map('map').setView([userLat, userLng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // Mark user's location
                L.marker([userLat, userLng]).addTo(map)
                    .bindPopup("You are here").openPopup();
            }

            // Fetch businesses from your PHP API
            fetch(`/your-app/business/search.php?lat=${userLat}&lng=${userLng}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    data.forEach(b => {
                        L.marker([b.latitude, b.longitude]).addTo(map)
                            .bindPopup(`<strong>${b.name}</strong><br>${b.address}<br>Category: ${b.category}<br>Distance: ${b.distance.toFixed(2)} km`);
                    });
                });
        }, function(err) {
            alert("Location access denied or failed.");
        });
    }
    </script>
</body>
</html>
