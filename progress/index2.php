<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require '../config/db.php';
$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch the user's company ID
$stmt = $pdo->prepare("SELECT company_id FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$company_id = $user['company_id'];

// Fetch the user's company name
$stmt = $pdo->prepare("SELECT name FROM companies WHERE id = ?");
$stmt->execute([$company_id]);
$company = $stmt->fetch(PDO::FETCH_ASSOC);
$company_name = $company['name'] ?? 'Unknown Company';

// Fetch courses available to the user's company
$stmt = $pdo->prepare("SELECT c.* FROM courses c INNER JOIN company_courses cc ON c.id = cc.course_id WHERE cc.company_id = ?");
$stmt->execute([$company_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user's course progress
$progress_stmt = $pdo->prepare("SELECT course_id, watched_video, took_quiz FROM progress WHERE user_id = ?");
$progress_stmt->execute([$user_id]);
$progress_data = $progress_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Progress</title>
    <link rel="stylesheet" href="../styles/courses.css">
</head>
<body>

<div class="top-right-link">
    <a href="../dashboard.php" class="back-button">Back to Dashboard</a>
</div>

<h1>📚 Course Progress</h1>
<p>Below are your progress and statuses for the courses in your company:</p>
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
                    
                    <?php
                    // Find the progress data for the current course
                    $progress = null;
                    foreach ($progress_data as $data) {
                        if ($data['course_id'] == $course['id']) {
                            $progress = $data;
                            break;
                        }
                    }
                    
                    // Set default values if no progress found
                    if (!$progress) {
                        $progress = ['watched_video' => 0, 'took_quiz' => 0];
                    }

                    // Calculate progress as a percentage
                    $progress_percentage = 0;
                    if ($progress['watched_video'] == 1) {
                        $progress_percentage += 50; // 50% for watching the video
                    }
                    if ($progress['took_quiz'] == 1) {
                        $progress_percentage += 50; // 50% for taking the quiz
                    }
                    ?>

                    <p><strong>Progress:</strong> <?= $progress_percentage ?>%</p>
                    
                    <!-- Always show the "View Results" button -->
                    <a class="btn" href="results.php?id=<?= $course['id'] ?>">View Results </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

</body>
</html>
