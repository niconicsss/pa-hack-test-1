<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

require '../config/db.php';

$user_id = $_SESSION['user_id'];

// Fetch the user's company ID
$stmt = $pdo->prepare("SELECT company_id FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit;
}

$company_id = $user['company_id'];

// Fetch the user's company name
$stmt = $pdo->prepare("SELECT name FROM companies WHERE id = ?");
$stmt->execute([$company_id]);
$company = $stmt->fetch(PDO::FETCH_ASSOC);
$company_name = $company['name'] ?? 'Unknown Company';

// Fetch courses available to the user's company
$stmt = $pdo->prepare("
    SELECT c.*
    FROM courses c
    INNER JOIN company_courses cc ON c.id = cc.course_id
    WHERE cc.company_id = ?
");
$stmt->execute([$company_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user's course progress
$progress_stmt = $pdo->prepare("SELECT course_id, status FROM progress WHERE user_id = ?");
$progress_stmt->execute([$user_id]);
$progress_data = $progress_stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Library</title>
    <link rel="stylesheet" href="../styles/courses.css">
</head>
<body>

<div class="top-right-link">
    <a href="../dashboard.php">← Back to Dashboard</a>
</div>

    <h1>📚 Course Library</h1>
    <p>Welcome! Below are your training courses for:</p>
    <h2 style="color: #333"><?= htmlspecialchars($company_name) ?></h2>

    <div class="course-list">
        <?php if (empty($courses)): ?>
            <p>No courses assigned to your company yet.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($courses as $course): ?>
                    <li class="course-item">
                        <h2><?= htmlspecialchars($course['title']) ?></h2>
                        <p><?= htmlspecialchars($course['description']) ?></p>
                        <p><strong>Level:</strong> <?= ucfirst($course['level']) ?></p>
                        <p><strong>Status:</strong> <?= htmlspecialchars($progress_data[$course['id']] ?? 'Not Started') ?></p>
                        <a class="btn" href="view.php?id=<?= $course['id'] ?>">Start Learning ➜</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
</body>
</html>
