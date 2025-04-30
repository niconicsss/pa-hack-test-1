<?php
require '../config/db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$business = $_POST['business'];
$radius = (int)$_POST['radius'];

<<<<<<< HEAD
    $sql = "INSERT INTO users (name, email, password, business, radius) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
=======
try {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, business, radius) VALUES (?, ?, ?, ?, ?)");
>>>>>>> fd9f8a942531f772d960526118208da50325efba
    $stmt->execute([$name, $email, $password, $business, $radius]);
    header("Location: ../index.php?registered=1");
} catch (PDOException $e) {
    echo "Registration failed: " . $e->getMessage();
}
?>
