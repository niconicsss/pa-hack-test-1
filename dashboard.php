<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require 'config/db.php';
$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logistics Learning Dashboard</title>
    <link rel="stylesheet" href="styles/dashboard.css">
</head>
<body>

    <!-- Top Bar -->
    <div class="logout-container">
        <span>Welcome, <?php echo htmlspecialchars($user['name']); ?></span>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <h1>📦 Logistics Skills Hub</h1>

    <div class="dashboard-sections">
        <section>
            <h2>📚 Course Library</h2>
            <p>Learn essential digital tools and logistics practices to stay competitive.</p>
            <a href="courses/index.php">Access Courses</a>
        </section>

        <section>
            <h2>📈 Progress Tracking</h2>
            <p>Track your learning journey and monitor your improvement over time.</p>
            <a href="progress/index.php">View Progress</a>
        </section>

        <section>
            <h2>🧠 Interactive Quizzes</h2>
            <p>Test your knowledge and reinforce what you've learned with quick assessments.</p>
            <a href="quizzes/index.php">Take a Quiz</a>
        </section>

        <section>
            <h2>📱 Mobile-Friendly Access</h2>
            <p>Learn anytime, anywhere – optimized for use on mobile devices.</p>
            <a href="mobile/index.php">Start Mobile Learning</a>
        </section>

        <section>
            <h2>👥 Community Forum</h2>
            <p>Connect with fellow workers, ask questions, and share logistics tips.</p>
            <a href="forums/index.php">Join the Discussion</a>
        </section>
    </div>

</body>
</html>
