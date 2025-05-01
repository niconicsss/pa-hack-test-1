<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all courses the user attempted
$stmt = $pdo->prepare("
    SELECT DISTINCT c.id AS course_id, c.title
    FROM courses c
    JOIN quizzes q ON c.id = q.course_id
    JOIN quiz_responses r ON q.id = r.quiz_id
    WHERE r.user_id = ?
");
$stmt->execute([$user_id]);
$courses = $stmt->fetchAll();

$progress = [];

foreach ($courses as $course) {
    $course_id = $course['course_id'];

    // Total number of quiz questions
    $totalStmt = $pdo->prepare("SELECT COUNT(*) FROM quizzes WHERE course_id = ?");
    $totalStmt->execute([$course_id]);
    $totalQuestions = $totalStmt->fetchColumn();

    // Correct answers
    $correctStmt = $pdo->prepare("
        SELECT COUNT(*) FROM quiz_responses r
        JOIN quizzes q ON r.quiz_id = q.id
        WHERE r.user_id = ? AND q.course_id = ? AND r.is_correct = 1
    ");
    $correctStmt->execute([$user_id, $course_id]);
    $correctAnswers = $correctStmt->fetchColumn();

    // Only show if 100% correct
    if ($correctAnswers == $totalQuestions && $totalQuestions > 0) {
        $progress[] = [
            'title' => $course['title'],
            'percentage' => 100,
            'status' => 'Completed'
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Completed Courses</title>
    <link rel="stylesheet" href="../styles/view-course.css">
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 2rem auto;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 0.75rem;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>

<h1>Courses You Completed 100%</h1>

<?php if (count($progress) > 0): ?>
    <table>
        <tr>
            <th>Course</th>
            <th>Score</th>
            <th>Status</th>
        </tr>
        <?php foreach ($progress as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td>100%</td>
                <td><?= $row['status'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p style="text-align:center;">You haven't completed any courses with 100% score yet.</p>
<?php endif; ?>

</body>
</html>
