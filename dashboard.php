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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 500px; width: 100%; margin-top: 20px; }
        button { margin-top: 10px; }
    </style>
</head>
<body>

    <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
    <h2>Your Business: <?php echo htmlspecialchars($user['business']); ?></h2>
    <p>Radius: <?php echo htmlspecialchars($user['radius']); ?> km</p>

    <!-- Button to search nearby businesses -->
    <button onclick="loadMap()">View Nearby Businesses</button>
    <div id="map"></div>

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
