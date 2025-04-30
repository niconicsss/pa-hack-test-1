<?php include('../includes/auth.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Place Order</title>
    <link rel="stylesheet" href="../styles/create-order.css">
</head>
<body>

<h1>Place an Order</h1>
<form action="../actions/place_order.php" method="POST">
    <label for="business_id">Business</label><br>
    <select name="business_id" id="business_id" required>
        <?php
        include('../config/db.php');
        $sql = "SELECT * FROM businesses";
        $stmt = $pdo->query($sql);
        while ($business = $stmt->fetch()) {
            echo "<option value='" . $business['id'] . "'>" . htmlspecialchars($business['name']) . "</option>";
        }
        ?>
    </select><br><br>

    <label for="order_details">Order Details:</label><br>
    <textarea name="order_details" id="order_details" rows="5" cols="40" required></textarea><br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>