<?php
session_start();
require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Handle new thread submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['content'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    if ($title && $content) {
        $stmt = $pdo->prepare("INSERT INTO forum_threads (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $title, $content]);
        header('Location: index.php');
        exit;
    }
}

// Fetch threads
$threads = $pdo->query("SELECT forum_threads.*, users.name FROM forum_threads 
                        JOIN users ON forum_threads.user_id = users.id 
                        ORDER BY forum_threads.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Community Forum</title>
</head>
<body>
    <h1>Community Forum</h1>
    <p>Connect with fellow workers, ask questions, and share logistics tips.</p>

    <h2>Start a New Thread</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Thread title" required><br>
        <textarea name="content" placeholder="What's on your mind?" required></textarea><br>
        <button type="submit">Post Thread</button>
    </form>

    <h2>Recent Discussions</h2>
    <?php foreach ($threads as $thread): ?>
        <div style="border:1px solid #ccc; margin-bottom:10px; padding:10px;">
            <h3><a href="thread.php?id=<?= $thread['id'] ?>"><?= htmlspecialchars($thread['title']) ?></a></h3>
            <p><?= nl2br(htmlspecialchars(substr($thread['content'], 0, 150))) ?>...</p>
            <small>Posted by <?= htmlspecialchars($thread['name']) ?> on <?= $thread['created_at'] ?></small>
        </div>
    <?php endforeach; ?>
</body>
</html>
