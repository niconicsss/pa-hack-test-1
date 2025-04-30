<?php
require '../includes/auth.php';
require '../config/db.php';

header('Content-Type: application/json');

if (!isset($_GET['lat'], $_GET['lng'], $_GET['category'], $_GET['radius'])) {
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

$userLat = (float) $_GET['lat'];
$userLng = (float) $_GET['lng'];
$category = $_GET['category'];
$radius = (float) $_GET['radius'];

$sql = "SELECT *, 
    (6371 * ACOS(
        COS(RADIANS(:lat)) * COS(RADIANS(latitude)) *
        COS(RADIANS(longitude) - RADIANS(:lng)) +
        SIN(RADIANS(:lat)) * SIN(RADIANS(latitude))
    )) AS distance
FROM businesses
WHERE category = :category
HAVING distance <= :radius
ORDER BY distance ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':lat' => $userLat,
    ':lng' => $userLng,
    ':category' => $category,
    ':radius' => $radius
]);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($results);
