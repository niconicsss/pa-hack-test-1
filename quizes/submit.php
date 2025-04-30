<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$course_id = $_POST['course_id'] ?? null;
$answers = $_POST['answers'] ?? [];

if (!$course_id || empty($answers)) {
    echo "Invalid submission.";
    exit;
}

$score = 0;
$total = count($answers);

echo "<h1>Quiz Results</h1>";

// Prepare once to insert responses
$insertStmt = $pdo->prepare("
    INSERT INTO quiz_responses (user_id, quiz_id, selected_option, is_correct)
    VALUES (?, ?, ?, ?)
");

foreach ($answers as $quiz_id => $selected_option) {
    // Get correct answer
    $stmt = $pdo->prepare("SELECT correct_option, question FROM quizzes WHERE id = ?");
    $stmt->execute([$quiz_id]);
    $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quiz) continue;

    $is_correct = ((int)$selected_option === (int)$quiz['correct_option']) ? 1 : 0;
    if ($is_correct) $score++;

    // Save response
    $insertStmt->execute([$user_id, $quiz_id, $selected_option, $is_correct]);

    echo "<p><strong>Question:</strong> " . htmlspecialchars($quiz['question']) . "<br>";
    echo $is_correct ? "<span style='color:green;'>✔ Correct</span>" : "<span style='color:red;'>✘ Incorrect</span>";
    echo "</p><hr>";
}

echo "<h2>Your Score: $score / $total</h2>";
echo "<p><a href='../courses/index.php'>← Back to Courses</a></p>";
?>
