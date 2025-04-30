<?php
require '../config/db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

if ($password !== $confirm_password) {
    echo "Passwords do not match.";
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $hashed_password]);

    header("Location: ../index.php?registered=1");
    exit;
} catch (PDOException $e) {
    echo "Registration failed: " . $e->getMessage();
}
?>
