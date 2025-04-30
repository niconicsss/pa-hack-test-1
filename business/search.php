<?php
// Include your database connection
include('../config/db.php');

// Get the lat and lng from the URL
$lat = isset($_GET['lat']) ? $_GET['lat'] : null;
$lng = isset($_GET['lng']) ? $_GET['lng'] : null;

// If lat or lng is missing, return an error
if (!$lat || !$lng) {
    echo json_encode(['error' => 'Missing lat/lng']);
    exit;
}

// Example of a query to fetch businesses within a certain radius
// Using the Haversine formula to calculate the distance between two latitudes/longitudes
$sql = "SELECT id, name, address, latitude, longitude, category, 
                ( 6371 * acos( cos( radians(?) ) * cos( radians(latitude) ) * cos( radians(longitude) - radians(?) ) + sin( radians(?) ) * sin( radians(latitude) ) ) ) AS distance
        FROM businesses
        HAVING distance < ?  // Example of radius filter in kilometers
        ORDER BY distance";

$stmt = $pdo->prepare($sql);
$stmt->execute([$lat, $lng, $lat, 10]);  // 10 km radius example
$businesses = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($businesses);
?>
