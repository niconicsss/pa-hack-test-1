<?php
require '../config/db.php';

header('Content-Type: application/json');

// Get latitude, longitude, and category from AJAX request
$userLat = $_POST['latitude'];
$userLon = $_POST['longitude'];
$category = $_POST['category'];
$radius = 5; // Example radius: 5 km

// Prepare SQL to fetch nearby businesses using the Haversine formula
$sql = "
    SELECT *, (
        6371 * ACOS(
            COS(RADIANS(:userLat)) * COS(RADIANS(latitude)) *
            COS(RADIANS(longitude) - RADIANS(:userLon)) +
            SIN(RADIANS(:userLat)) * SIN(RADIANS(latitude))
        )
    ) AS distance
    FROM businesses
    WHERE category = :category
    HAVING distance <= :radius
    ORDER BY distance ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':userLat' => $userLat,
    ':userLon' => $userLon,
    ':category' => $category,
    ':radius' => $radius
]);

$businesses = $stmt->fetchAll();

// Return the data as JSON
echo json_encode($businesses);
?>
