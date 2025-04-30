<?php
include('../includes/auth.php');
include('../config/db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Place Order</title>
</head>
<body>

<h2>Place an Order</h2>

<form action="../actions/place_order.php" method="POST">
    <label for="business_id">Business</label><br>
    <select name="business_id" id="business_id" required>
        <?php
        try {
            $sql = "SELECT id, name FROM businesses";
            $stmt = $pdo->query($sql);

            while ($business = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = htmlspecialchars($business['id']);
                $name = htmlspecialchars($business['name']);
                echo "<option value='{$id}'>{$name}</option>";
            }
        } catch (PDOException $e) {
            echo "<option disabled>Error loading businesses</option>";
            // Optionally log the error: error_log($e->getMessage());
        }
        ?>
    </select><br><br>

    <label for="order_details">Order Details:</label><br>
    <textarea name="order_details" id="order_details" rows="5" cols="40" required></textarea><br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>