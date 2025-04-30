<?php
include('../config/db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $business_id = $_POST['business_id'];
    $order_details = $_POST['order_details'];

    $sql = "INSERT INTO orders (user_id, business_id, order_details, status) VALUES (?, ?, ?, 'Pending')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $business_id, $order_details]);

    header('Location: ../orders/history.php'); // Redirect to order history
}
?>
