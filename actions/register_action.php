<?php
include('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $business = $_POST['business'];
    $radius = $_POST['radius'];

    // Prepare the SQL query
    $sql = "INSERT INTO users (name, email, password, business, radius) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $password, $business, $radius]);

    // Redirect to login page
    header('Location: ../login.php');
}
?>
