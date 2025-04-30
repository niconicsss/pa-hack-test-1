<?php
$host = 'localhost';
$dbname = 'db'; // Make sure this DB exists!
$username = 'root';
$password = 'your_password_here'; // <-- change this

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>