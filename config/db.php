<?php
$host = 'localhost';      // Database host
$dbname = 'business_orders_db'; // Database name
$username = 'root'; // Database username
$password = 'root'; // Database password

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
