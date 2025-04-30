<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM orders WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);

$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
</head>
<body>

<h2>Your Orders</h2>

<?php foreach ($orders as $order): ?>
    <p>Order ID: <?php echo $order['id']; ?> | Status: <?php echo $order['status']; ?></p>
    <p>Details: <?php echo htmlspecialchars($order['order_details']); ?></p>
<?php endforeach; ?>

</body>
</html>
