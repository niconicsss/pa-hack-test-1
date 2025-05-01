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
$course_id = $_GET['id']; // Get course ID from URL
$stmt = $pdo->prepare("SELECT c.* FROM courses c INNER JOIN company_courses cc ON c.id = cc.course_id WHERE cc.company_id = ?");
$stmt->execute([$company_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user's progress for the specific course
$progress_stmt = $pdo->prepare("SELECT watched_video, took_quiz FROM progress WHERE user_id = ? AND course_id = ?");
$progress_stmt->execute([$user_id, $course_id]);
$progress = $progress_stmt->fetch(PDO::FETCH_ASSOC);

// Calculate progress percentages
$video_progress = 0;
$quiz_progress = 0;
$progress_percentage = 0;

// Handle progress status
$video_status = 'Not Completed';
$quiz_status = 'Not Completed';
$total_status = 'Not Started';

if ($progress) {
    if ($progress['watched_video'] == 1) {
        $video_progress = 50;
        $video_status = 'Completed';
    }
    if ($progress['took_quiz'] == 1) {
        $quiz_progress = 50;
        $quiz_status = 'Completed';
    }

    $progress_percentage = $video_progress + $quiz_progress;

    if ($progress_percentage == 100) {
        $total_status = 'Completed';
    } elseif ($progress_percentage > 0) {
        $total_status = 'In Progress';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Results</title>
    <link rel="stylesheet" href="styles/course-result.css">
</head>
<body>

<div class="top-right-link">
    <a href="../dashboard.php" class="back-button">← Back to Dashboard</a>
</div>

<h1>📚 Course Results</h1>
<p>Below are your progress and statuses for the course:</p>
<h2 style="color: #333"><?= htmlspecialchars($courses[0]['title']) ?></h2>

<!-- Table for progress -->
<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>Progress Component</th>
            <th>Status</th>
            <th>Percentage</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Video</td>
            <td><?= $video_status ?></td>
            <td><?= $video_progress ?>%</td>
        </tr>
        <tr>
            <td>Quiz</td>
            <td><?= $quiz_status ?></td>
            <td><?= $quiz_progress ?>%</td>
        </tr>
        <tr>
            <td><strong>Total Progress</strong></td>
            <td><strong><?= $total_status ?></strong></td>
            <td><strong><?= $progress_percentage ?>%</strong></td>
        </tr>
    </tbody>
</table>

</body>
</html>
