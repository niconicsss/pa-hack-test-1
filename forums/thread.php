<?php
session_start();
require_once '../config/db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$threadId = (int)$_GET['id'];

// Fetch thread
$stmt = $pdo->prepare("SELECT forum_threads.*, users.name FROM forum_threads 
                       JOIN users ON forum_threads.user_id = users.id 
                       WHERE forum_threads.id = ?");
$stmt->execute([$threadId]);
$thread = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$thread) {
    echo "Thread not found.";
    exit;
}

// Handle reply
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'], $_POST['content'])) {
    $replyContent = trim($_POST['content']);
    if ($replyContent) {
        $replyStmt = $pdo->prepare("INSERT INTO forum_replies (thread_id, user_id, content) VALUES (?, ?, ?)");
        $replyStmt->execute([$threadId, $_SESSION['user_id'], $replyContent]);
        header("Location: thread.php?id=" . $threadId);
        exit;
    }
}

// Get replies
$replies = $pdo->prepare("SELECT forum_replies.*, users.name FROM forum_replies 
                          JOIN users ON forum_replies.user_id = users.id 
                          WHERE forum_replies.thread_id = ? ORDER BY forum_replies.created_at ASC");
$replies->execute([$threadId]);
$replies = $replies->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($thread['title']) ?> - Forum</title>
</head>
<body>
    <h1><?= htmlspecialchars($thread['title']) ?></h1>
    <p><?= nl2br(htmlspecialchars($thread['content'])) ?></p>
    <small>Posted by <?= htmlspecialchars($thread['name']) ?> on <?= $thread['created_at'] ?></small>

    <hr>
    <h2>Replies</h2>
    <?php foreach ($replies as $reply): ?>
        <div style="margin-bottom:10px;">
            <p><?= nl2br(htmlspecialchars($reply['content'])) ?></p>
            <small>By <?= htmlspecialchars($reply['name']) ?> on <?= $reply['created_at'] ?></small>
        </div>
    <?php endforeach; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <h3>Post a Reply</h3>
        <form method="POST">
            <textarea name="content" required></textarea><br>
            <button type="submit">Reply</button>
        </form>
    <?php else: ?>
        <p><a href="../login.php">Log in</a> to reply.</p>
    <?php endif; ?>
</body>
</html>
