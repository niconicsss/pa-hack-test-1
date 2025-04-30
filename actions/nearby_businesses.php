<?php
require '../config/db.php';
header('Content-Type: application/json');

// Check if category is provided
if (!isset($_POST['category'])) {
    echo json_encode(['error' => 'Missing category']);
    exit;
}

$category = $_POST['category'];

// Fetch all businesses with the selected category
$sql = "SELECT * FROM businesses WHERE category = :category ORDER BY name ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':category' => $category]);
$results = $stmt->fetchAll();

echo json_encode($results);
