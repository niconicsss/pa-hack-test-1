<?php
session_start();
require_once '../config/db.php';

// Assume the user is logged in and order_id comes from a form or URL
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $payment_method = $_POST['payment_method'];
    $amount = $_POST['amount'];
    $payment_status = $_POST['payment_status'];
    
    // Handle file upload
    $proof_path = null;
    if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../uploads/";
        $filename = basename($_FILES['payment_proof']['name']);
        $proof_path = $target_dir . time() . "_" . $filename;
        move_uploaded_file($_FILES['payment_proof']['tmp_name'], $proof_path);
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO payments (order_id, payment_method, amount, payment_status, payment_proof) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$order_id, $payment_method, $amount, $payment_status, $proof_path]);

        $_SESSION['success'] = "Payment submitted successfully.";
        header('Location: ../payments/confirm.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to submit payment: " . $e->getMessage();
        header('Location: ../payments/confirm.php');
        exit;
    }
} else {
    header('Location: ../index.php');
    exit;
}
