<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $company_email = $_POST['company_email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $company_id = $_POST['company_id'];

    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("SELECT email_domain FROM companies WHERE id = :company_id");
        $stmt->bindParam(':company_id', $company_id);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            echo "Invalid company selection!";
            exit;
        }

        $company = $stmt->fetch(PDO::FETCH_ASSOC);
        $company_domain = $company['email_domain'];
        $email_domain = substr(strrchr($company_email, "@"), 1);

        if ($email_domain !== $company_domain) {
            echo "The email domain does not match the selected company. Please use your official company email!";
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, company_id, role) 
                               VALUES (:name, :email, :password_hash, :company_id, :role)");
        $role = 'learner';
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $company_email); // Using company_email as main email
        $stmt->bindParam(':password_hash', $password_hash);
        $stmt->bindParam(':company_id', $company_id);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        echo "Registration successful! You can now <a href='../login.php'>login</a>.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
