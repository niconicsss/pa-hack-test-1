<?php
require '../includes/auth.php';
require '../config/db.php';
$userId = $_SESSION['user_id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

$category = $user['business'];
$radius = $user['radius'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nearby Businesses</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <style>
        #map {
            width: 100%;
            height: 500px;
        }
    </style>
</head>
<body class="container mt-4">

    <h2>Nearby Suppliers (<?= htmlspecialchars($category) ?>)</h2>
    <div id="map"></div>
    <div id="businessList" class="row mt-4"></div>

    <script>
        const category = "<?= $category ?>";
        const radius = <?= $radius ?>;
        let userLat = null;
        let userLng = null;

        function initMap() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    userLat = pos.coords.latitude;
                    userLng = pos.coords.longitude;

                    const userLocation = { lat: userLat, lng: userLng };
                    const map = new google.maps.Map(document.getElementById("map"), {
                        zoom: 13,
                        center: userLocation
                    });

                    new google.maps.Marker({
                        position: userLocation,
                        map,
                        title: "You are here",
                        icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                    });

                    fetch(`/business/search.php?lat=${userLat}&lng=${userLng}&category=${category}&radius=${radius}`)
                        .then(res => res.json())
                        .then(data => {
                            if (Array.isArray(data)) {
                                data.forEach(business => {
                                    const position = { lat: parseFloat(business.latitude), lng: parseFloat(business.longitude) };

                                    new google.maps.Marker({
                                        position,
                                        map,
                                        title: business.name
                                    });

                                    document.getElementById("businessList").innerHTML += `
                                        <div class="col-md-4">
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <h5>${business.name}</h5>
                                                    <p>${business.address}</p>
                                                    <p class="text-muted">${business.distance.toFixed(2)} km away</p>
                                                    <a href="/orders/create.php?business_id=${business.id}" class="btn btn-sm btn-primary">Order</a>
                                                </div>
                                            </div>
                                        </div>`;
                                });
                            } else {
                                alert("No nearby businesses found.");
                            }
                        });
                }, () => {
                    alert("Geolocation failed.");
                });
            } else {
                alert("Geolocation not supported.");
            }
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_REAL_API_KEY&callback=initMap" async defer></script>
</body>
</html>
