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

?>
<!DOCTYPE html>
<html>
<head>
    <title>Quiz Results</title>
    <link rel="stylesheet" href="../styles/quiz.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9; }
        .question { margin-bottom: 20px; }
        .correct { color: green; }
        .incorrect { color: red; }
        .score { font-size: 1.2em; font-weight: bold; margin-top: 30px; }
        a { text-decoration: none; color: #007BFF; }
    </style>
</head>
<body>

<h1>📝 Quiz Results</h1>

<?php
// Prepare once to insert responses
$insertStmt = $pdo->prepare("
    INSERT INTO quiz_responses (user_id, quiz_id, selected_option, is_correct)
    VALUES (?, ?, ?, ?)
");

foreach ($answers as $quiz_id => $selected_option) {
    $stmt = $pdo->prepare("SELECT correct_option, question FROM quizzes WHERE id = ?");
    $stmt->execute([$quiz_id]);
    $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quiz) continue;

    $is_correct = ((int)$selected_option === (int)$quiz['correct_option']) ? 1 : 0;
    if ($is_correct) $score++;

    $insertStmt->execute([$user_id, $quiz_id, $selected_option, $is_correct]);

    echo "<div class='question'>";
    echo "<strong>Question:</strong> " . htmlspecialchars($quiz['question']) . "<br>";
    echo $is_correct
        ? "<span class='correct'>✔ Correct</span>"
        : "<span class='incorrect'>✘ Incorrect</span>";
    echo "</div><hr>";
}
?>

<div class="score">
    Your Score: <?= $score ?> / <?= $total ?> (<?= round(($score / $total) * 100) ?>%)
</div>

<p><a href="../dashboard.php">← Back to Dashboard</a></p>

</body>
</html>
