<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require '../config/db.php';
$user_id = $_SESSION['user_id'];

// Get user info including company
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$company_id = $user['company_id'];

if (!$company_id) {
    echo "You are not associated with a company.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select a Quiz - SkillCargo</title>
    <link rel="stylesheet" href="../styles/quizzes2.css"> <!-- Adjusted relative path -->

</head>
<body>

    <div class="top-right-button">
        <a href="../dashboard.php">Back to Dashboard</a>
    </div>

    <h1>🧠 Take a Quiz</h1>
    <p>Select a course below to begin the quiz:</p>

    <?php
    $stmt = $pdo->prepare("
        SELECT c.id, c.title 
        FROM courses c
        JOIN company_courses cc ON c.id = cc.course_id
        WHERE cc.company_id = ?
    ");
    $stmt->execute([$company_id]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($courses):
        foreach ($courses as $course):
    ?>
        <div class="course-box">
            <div class="course-title"><?= htmlspecialchars($course['title']) ?></div>
            <form action="../quizes/index.php" method="get">
                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                <button class="quiz-button" type="submit">Take Quiz</button>
            </form>
        </div>
    <?php
        endforeach;
    else:
        echo "<p>No available quizzes for your company.</p>";
    endif;
    ?>
</body>

</html>
