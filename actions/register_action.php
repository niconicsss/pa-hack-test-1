<?php
require '../config/db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

try {
    // Insert only user details (no business or radius)
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $password]);

    header("Location: ../index.php?registered=1");
    exit;
} catch (PDOException $e) {
    echo "Registration failed: " . $e->getMessage();
}
?>
