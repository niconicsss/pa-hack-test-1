<?php
require '../config/db.php';
session_start();

$course_id = $_GET['id'] ?? null;
if (!$course_id) {
    echo "Course not found.";
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "You must be logged in to view this course.";
    exit;
}

// Get course
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

if (!$course) {
    echo "Course not found.";
    exit;
}

// Check progress
$progressStmt = $pdo->prepare("SELECT * FROM progress WHERE user_id = ? AND course_id = ?");
$progressStmt->execute([$user_id, $course_id]);
$progress = $progressStmt->fetch();

if (!$progress) {
    // Insert new progress record
    $insert = $pdo->prepare("INSERT INTO progress (user_id, course_id, status, watched_video) VALUES (?, ?, 'in-progress', 1)");
    $insert->execute([$user_id, $course_id]);
} else {
    // Update video watched
    if (!$progress['watched_video']) {
        $update = $pdo->prepare("UPDATE progress SET watched_video = 1 WHERE user_id = ? AND course_id = ?");
        $update->execute([$user_id, $course_id]);
    }

    // If both video watched and quiz taken, set to complete
    if ($progress['watched_video'] && $progress['took_quiz'] && $progress['status'] !== 'completed') {
        $complete = $pdo->prepare("UPDATE progress SET status = 'completed' WHERE user_id = ? AND course_id = ?");
        $complete->execute([$user_id, $course_id]);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($course['title']) ?> - Course View</title>
    <link rel="stylesheet" href="../styles/view-course.css">
</head>
<body>

    <h1><?= htmlspecialchars($course['title']) ?></h1>
    <p><?= nl2br(htmlspecialchars($course['description'])) ?></p>

    <?php if ($course['video_url']): ?>
        <video controls width="640">
            <source src="<?= htmlspecialchars($course['video_url']) ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    <?php endif; ?>

    <a class="btn" href="../quizes/index.php?course_id=<?= $course['id'] ?>">Take Quiz 📝</a>
    <p><a href="../progress/index.php">Track Progress</a></p>

</body>
</html>
