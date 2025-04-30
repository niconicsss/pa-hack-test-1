<?php include('../includes/auth.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Place Order</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Verdana, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .order-form {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }

        label {
            display: block;
            margin: 0.5rem 0 0.2rem;
            font-weight: bold;
        }

        select,
        textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        button[type="submit"] {
            width: 100%;
            padding: 0.75rem;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="order-form">
    <h2>Place an Order</h2>
    <form action="../actions/place_order.php" method="POST">
        <label for="business_id">Business</label>
        <select name="business_id" id="business_id" required>
            <?php
            include('../config/db.php');
            $sql = "SELECT * FROM businesses";
            $stmt = $pdo->query($sql);
            while ($business = $stmt->fetch()) {
                echo "<option value='" . $business['id'] . "'>" . htmlspecialchars($business['name']) . "</option>";
            }
            ?>
        </select>

        <label for="order_details">Order Details</label>
        <textarea name="order_details" id="order_details" rows="5" required></textarea>

        <button type="submit">Place Order</button>
    </form>
</div>

</body>
</html>