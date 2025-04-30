<?php
session_start();
require_once('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT o.id, o.order_details, o.status, o.created_at, b.name AS business_name
        FROM orders o
        JOIN businesses b ON o.business_id = b.id
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Your Orders</title>
    <link rel="stylesheet" href="styles/order-status.css">
</head>
<body>

    <h1>Track Your Orders</h1>

    <?php if (count($orders) > 0): ?>
        <?php foreach ($orders as $order): ?>
            <div class="order-status">
                <h3>Order #<?php echo htmlspecialchars($order['id']); ?> - <?php echo htmlspecialchars($order['business_name']); ?></h3>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
                <p><strong>Details:</strong> <?php echo nl2br(htmlspecialchars($order['order_details'])); ?></p>
                <p><small>Ordered on: <?php echo date("F j, Y, g:i a", strtotime($order['created_at'])); ?></small></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You have no orders to track.</p>
    <?php endif; ?>

    <a class="button" href="../dashboard.php">⬅ Back to Dashboard</a>

</body>
</html>