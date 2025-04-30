<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

include('../config/db.php');
$categories = ['Water Supply Store', 'Laundry', 'Restaurant'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nearby Businesses</title>
    <link rel="stylesheet" href="../styles/list.css">
</head>
<body>

<h1>Find Nearby Businesses</h1>

<form id="search-form">
    <label for="category">Category:</label>
    <select name="category" id="category" required>
        <option value="">-- Select Category --</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="radius">Radius (km):</label>
    <input type="number" name="radius" id="radius" value="5" min="1" required>

    <button type="submit">Search</button>
</form>

<div id="location-info"><p>Detecting your location...</p></div>

<h2>Results</h2>
<div id="business-results"></div>

<script>
let userLat = null;
let userLon = null;

// Get user location on page load
document.addEventListener("DOMContentLoaded", () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            userLat = position.coords.latitude;
            userLon = position.coords.longitude;
            document.getElementById('latitude').value = userLat;
            document.getElementById('longitude').value = userLon;
            document.getElementById('location-info').innerHTML = 
                `<p>Your Location: ${userLat}, ${userLon}</p>`;
        }, error => {
            document.getElementById('location-info').innerHTML = 
                "<p>Unable to get your location.</p>";
        });
    } else {
        document.getElementById('location-info').innerHTML = 
            "<p>Geolocation not supported.</p>";
    }

    // Submit form
    document.getElementById('search-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const category = document.getElementById('category').value;
        const radius = document.getElementById('radius').value;

        if (!userLat || !userLon) {
            alert("Location not available yet. Please wait a moment.");
            return;
        }

        fetchNearbyBusinesses(userLat, userLon, category, radius);
    });
});

// Fetch function
function fetchNearbyBusinesses(lat, lon, category, radius) {
    fetch('../actions/nearby_businesses.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `latitude=${lat}&longitude=${lon}&category=${encodeURIComponent(category)}&radius=${radius}`
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('business-results');
        container.innerHTML = '';

        if (Array.isArray(data) && data.length > 0) {
            data.forEach(b => {
                const el = document.createElement('div');
                el.classList.add('business');
                el.innerHTML = `
                    <h3>${b.name}</h3>
                    <p>Category: ${b.category}</p>
                    <p>Distance: ${b.distance.toFixed(2)} km</p>
                    <p>Address: ${b.address}</p>
                `;
                container.appendChild(el);
            });
        } else {
            container.innerHTML = "<p>No businesses found.</p>";
        }
    })
    .catch(err => {
        console.error("Fetch error:", err);
        document.getElementById('business-results').innerHTML = "<p>Error loading results.</p>";
    });
}
</script>

</body>
</html>
