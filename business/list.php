<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Redirect if not logged in
    exit;
}

include('../config/db.php');

// Get available business categories (You can customize this list)
$categories = ['Water Supply Store', 'Laundry', 'Restaurant']; // Add more categories as needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Nearby Businesses</title>
    <link rel="stylesheet" href="styles/list.css">
</head>
<body>

    <h1>Search Nearby Businesses</h1>

    <!-- Form to choose category -->
    <form method="GET" action="list.php">
        <label for="category">Select Business Type:</label>
        <select name="category" id="category" required>
            <option value="">-- Choose --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= isset($_GET['category']) && $_GET['category'] === $cat ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Search</button>
    </form>

    <!-- Display user's current location (optional, just for verification) -->
    <div id="location-info">
        <p>Getting your location...</p>
    </div>

    <!-- Button to trigger geolocation -->
    <button onclick="getUserLocation()">Use My Location</button>

    <!-- Display businesses -->
    <h2>Nearby Businesses</h2>
    <div id="business-results">
        <!-- Businesses will be displayed here -->
    </div>

    <script>
    // Function to get user's geolocation and send it to the server using AJAX
    function getUserLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var userLat = position.coords.latitude;
                var userLon = position.coords.longitude;
                
                // Display the coordinates (optional)
                document.getElementById('location-info').innerHTML = 
                    `<p>Your Location: ${userLat}, ${userLon}</p>`;
                
                // Get the selected category from the form
                var category = document.getElementById('category').value;

                // Make the AJAX request to fetch nearby businesses
                fetchNearbyBusinesses(userLat, userLon, category);
            }, function(error) {
                console.error("Geolocation error: " + error.message);
                alert("Unable to retrieve your location.");
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    // Function to fetch nearby businesses from the PHP backend
    function fetchNearbyBusinesses(userLat, userLon, category) {
        fetch('actions/nearby_businesses.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `latitude=${userLat}&longitude=${userLon}&category=${category}`
        })
        .then(response => response.json())
        .then(data => {
            // Clear previous results
            var resultsContainer = document.getElementById('business-results');
            resultsContainer.innerHTML = ''; 

            if (data.length > 0) {
                // Loop through businesses and display them
                data.forEach(business => {
                    var businessElement = document.createElement('div');
                    businessElement.classList.add('business');

                    businessElement.innerHTML = `
                        <h3>${business.name}</h3>
                        <p>Category: ${business.category}</p>
                        <p>Distance: ${business.distance.toFixed(2)} km</p>
                        <p>Address: ${business.address}</p>
                    `;
                    
                    resultsContainer.appendChild(businessElement);
                });
            } else {
                resultsContainer.innerHTML = "<p>No businesses found in this category within your radius.</p>";
            }
        })
        .catch(error => console.error('Error fetching nearby businesses:', error));
    }
    </script>

</body>
</html>
