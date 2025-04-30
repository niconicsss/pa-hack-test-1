<?php
require '../config/db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$business = $_POST['business'];
$radius = (int)$_POST['radius'];

try {
    $sql = "INSERT INTO users (name, email, password, business, radius) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $password, $business, $radius]);
    header("Location: ../index.php?registered=1");
    exit;
} catch (PDOException $e) {
    echo "Registration failed: " . $e->getMessage();
}