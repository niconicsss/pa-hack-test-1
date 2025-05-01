<?php
require '../config/db.php';

$course_id = $_GET['id'] ?? null;
if (!$course_id) {
    echo "Course not found.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

if (!$course) {
    echo "Course not found.";
    exit;
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