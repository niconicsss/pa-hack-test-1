<?php require_once '../includes/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nearby Suppliers</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body { font-family: Arial, sans-serif; }
        #map { height: 400px; width: 100%; margin-top: 20px; }
        #results { margin-top: 20px; }
        .supplier {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <h2>Nearby Suppliers</h2>

    <div id="map"></div>
    <div id="results">Loading suppliers...</div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    let map;

    function loadNearbySuppliers() {
        if (!navigator.geolocation) {
            document.getElementById('results').innerText = "Geolocation not supported.";
            return;
        }

        navigator.geolocation.getCurrentPosition(pos => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            if (!map) {
                map = L.map('map').setView([lat, lng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // Mark user location
                L.marker([lat, lng]).addTo(map)
                    .bindPopup("You are here").openPopup();
            }

            fetch(`search.php?lat=${lat}&lng=${lng}`)
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('results');
                    container.innerHTML = '';

                    if (data.error) {
                        container.innerText = data.error;
                        return;
                    }

                    if (data.length === 0) {
                        container.innerText = 'No suppliers found nearby.';
                        return;
                    }

                    data.forEach(supplier => {
                        // Add to list
                        const div = document.createElement('div');
                        div.className = 'supplier';
                        div.innerHTML = `
                            <strong>${supplier.name}</strong><br>
                            ${supplier.address}<br>
                            Distance: ${parseFloat(supplier.distance).toFixed(2)} km
                        `;
                        container.appendChild(div);

                        // Add to map
                        L.marker([supplier.latitude, supplier.longitude]).addTo(map)
                            .bindPopup(`<strong>${supplier.name}</strong><br>${supplier.address}<br>${supplier.distance.toFixed(2)} km`);
                    });
                })
                .catch(err => {
                    document.getElementById('results').innerText = 'Error fetching data.';
                    console.error(err);
                });

        }, () => {
            document.getElementById('results').innerText = "Location access denied.";
        });
    }

    loadNearbySuppliers();
    </script>
</body>
</html>
