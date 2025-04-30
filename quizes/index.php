<?php
session_start();
require '../config/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

if (!$course_id) {
    echo "Invalid course selected.";
    exit;
}

// Get the user's company
$stmt = $pdo->prepare("SELECT company_id FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$company_id = $user['company_id'] ?? null;
if (!$company_id) {
    echo "User is not associated with a company.";
    exit;
}

// Verify if this course is assigned to the user's company
$stmt = $pdo->prepare("SELECT 1 FROM company_courses WHERE company_id = ? AND course_id = ?");
$stmt->execute([$company_id, $course_id]);
if (!$stmt->fetchColumn()) {
    echo "This course is not assigned to your company.";
    exit;
}

// Fetch course info
$stmt = $pdo->prepare("SELECT title FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$course) {
    echo "Course not found.";
    exit;
}

// Fetch 5 random quiz questions for this course
$stmt = $pdo->prepare("
    SELECT * FROM quizzes
    WHERE course_id = ?
    ORDER BY RAND()
    LIMIT 5
");
$stmt->execute([$course_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($questions)) {
    echo "No quiz questions found for this course.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($course['title']) ?> - Quiz</title>
    <link rel="stylesheet" href="../styles/quiz.css">
</head>
<body>
    <h1>📝 Quiz: <?= htmlspecialchars($course['title']) ?></h1>

    <form action="submit.php" method="POST">
        <input type="hidden" name="course_id" value="<?= $course_id ?>">
        
        <?php foreach ($questions as $index => $q): ?>
            <div class="question-block">
                <p><strong>Q<?= $index + 1 ?>:</strong> <?= htmlspecialchars($q['question']) ?></p>
                <?php for ($i = 1; $i <= 4; $i++): 
                    $opt = $q["option_$i"];
                    if (!$opt) continue;
                ?>
                    <label>
                        <input type="radio" name="answers[<?= $q['id'] ?>]" value="<?= $i ?>" required>
                        <?= htmlspecialchars($opt) ?>
                    </label><br>
                <?php endfor; ?>
            </div>
            <hr>
        <?php endforeach; ?>

        <button type="submit">Submit Quiz</button>
    </form>

    <p><a href="../courses/index.php">← Back to Courses</a></p>
</body>
</html>
