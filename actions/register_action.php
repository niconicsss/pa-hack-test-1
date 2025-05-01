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
        // Check if the company exists and get its email domain
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

        // Insert the user
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, company_id, role) 
                               VALUES (:name, :email, :password_hash, :company_id, :role)");
        $role = 'learner';
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $company_email);
        $stmt->bindParam(':password_hash', $password_hash);
        $stmt->bindParam(':company_id', $company_id);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        // Optional: Start session and set session variables
        session_start();
        $_SESSION['user_email'] = $company_email;
        $_SESSION['user_name'] = $name;

        // Redirect to dashboard
        header("Location: ../dashboard.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}
?>
