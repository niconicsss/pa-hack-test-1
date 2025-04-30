<?php
include('../includes/auth.php');
include('../config/db.php');

// Check if data is posted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $business_id = $_POST['business_id'];
    $order_details = $_POST['order_details'];
    $user_id = $_SESSION['user_id']; // Make sure this is set in your auth session

    // Insert order into the database
    $sql = "INSERT INTO orders (user_id, business_id, order_details, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([$user_id, $business_id, $order_details]);
        header("Location: ../pages/order_success.php");
        exit();
    } catch (PDOException $e) {
        echo "Error placing order: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}