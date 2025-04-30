<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

header('Content-Type: application/json');

// Validate user session
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

// Get user info
$sql = "SELECT business, radius FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(['error' => 'User not found']);
    exit;
}

$category = $user['business'];
$radius = $user['radius'];

// Validate coordinates
$lat = $_GET['lat'] ?? null;
$lng = $_GET['lng'] ?? null;
if (!$lat || !$lng) {
    echo json_encode(['error' => 'Missing lat/lng']);
    exit;
}

// Find nearby suppliers using Haversine formula
$sql = "
    SELECT id, name, address, latitude, longitude, category,
        (6371 * ACOS(
            COS(RADIANS(:lat)) * COS(RADIANS(latitude)) *
            COS(RADIANS(longitude) - RADIANS(:lng)) +
            SIN(RADIANS(:lat)) * SIN(RADIANS(latitude))
        )) AS distance
    FROM businesses
    WHERE category = :category
    HAVING distance <= :radius
    ORDER BY distance ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':lat' => $lat,
    ':lng' => $lng,
    ':category' => $category,
    ':radius' => $radius
]);

$results = $stmt->fetchAll();

echo json_encode($results);
exit;
