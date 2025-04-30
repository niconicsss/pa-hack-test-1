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
<<<<<<< HEAD
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
=======
>>>>>>> 69b7e1d67c0bcf00dc9ee84900434994722fd75b
</head>
<body>

<<<<<<< HEAD
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
=======
>>>>>>> 69b7e1d67c0bcf00dc9ee84900434994722fd75b
    <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
    <h2>Your Business: <?php echo htmlspecialchars($user['business']); ?></h2>
    <p>Radius: <?php echo htmlspecialchars($user['radius']); ?> km</p>

<<<<<<< HEAD
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
=======
    <!-- Button to search nearby businesses -->
    <a href="business/search.php">Search Nearby Businesses</a>

    <!-- Link to view orders -->
    <a href="orders/history.php">View Orders</a>

    <!-- Link to track orders -->
    <a href="orders/status.php">Track Orders</a>

    <!-- Button to log out -->
    <a href="includes/logout.php">Logout</a>
>>>>>>> 69b7e1d67c0bcf00dc9ee84900434994722fd75b

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