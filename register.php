<?php include 'config/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles/register.css">
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>

        <?php if (isset($_GET['error'])): ?>
            <p class="error-msg"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>

        <form action="actions/register_action.php" method="POST">
            <input type="text" name="name" placeholder="Full Name (Last Name, First Name, M.I)" required>

            <input type="email" name="company_email" placeholder="Company Email (e.g., user@company.com)" required>

            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>

            <label for="company_id">Select your Company</label>
            <select name="company_id" id="company_id" required>
                <option value="">-- Select Company --</option>
                <?php
                try {
                    $stmt = $pdo->query("SELECT id, name FROM companies ORDER BY name ASC");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                    }
                } catch (PDOException $e) {
                    echo "<option disabled>Error loading companies</option>";
                }
                ?>
            </select>

            <button type="submit">Register</button>
        </form>
        <div class="bottom-link">
            <a href="login.php">Already have an account? <strong>Login</strong></a>
        </div>
    </div>
</body>
</html>
