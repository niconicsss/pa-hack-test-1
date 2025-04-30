<?php
session_start();
require_once '../config/db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$threadId = (int)$_GET['id'];
$userId = $_SESSION['user_id'] ?? null;
$editingReplyId = $_GET['edit_reply'] ?? null;

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

// Handle reply submission, update, or delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($userId)) {
    // New reply
    if (isset($_POST['content']) && empty($_POST['reply_id'])) {
        $replyContent = trim($_POST['content']);
        if ($replyContent) {
            $stmt = $pdo->prepare("INSERT INTO forum_replies (thread_id, user_id, content) VALUES (?, ?, ?)");
            $stmt->execute([$threadId, $userId, $replyContent]);
        }
        header("Location: thread.php?id=$threadId");
        exit;
    }

    // Update reply
    if (isset($_POST['reply_id'], $_POST['content'])) {
        $replyId = (int)$_POST['reply_id'];
        $replyContent = trim($_POST['content']);

        // Check ownership
        $stmt = $pdo->prepare("SELECT * FROM forum_replies WHERE id = ? AND user_id = ?");
        $stmt->execute([$replyId, $userId]);
        $reply = $stmt->fetch();
        if ($reply && $replyContent) {
            $stmt = $pdo->prepare("UPDATE forum_replies SET content = ? WHERE id = ?");
            $stmt->execute([$replyContent, $replyId]);
        }

        header("Location: thread.php?id=$threadId");
        exit;
    }

    // Delete reply
    if (isset($_POST['delete_reply_id'])) {
        $replyId = (int)$_POST['delete_reply_id'];

        // Check ownership
        $stmt = $pdo->prepare("SELECT * FROM forum_replies WHERE id = ? AND user_id = ?");
        $stmt->execute([$replyId, $userId]);
        $reply = $stmt->fetch();
        if ($reply) {
            $stmt = $pdo->prepare("DELETE FROM forum_replies WHERE id = ?");
            $stmt->execute([$replyId]);
        }

        header("Location: thread.php?id=$threadId");
        exit;
    }
}

// Fetch replies
$stmt = $pdo->prepare("SELECT forum_replies.*, users.name FROM forum_replies 
                       JOIN users ON forum_replies.user_id = users.id 
                       WHERE forum_replies.thread_id = ? ORDER BY forum_replies.created_at ASC");
$stmt->execute([$threadId]);
$replies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If editing a reply, fetch it
$editingReply = null;
if ($editingReplyId && $userId) {
    $stmt = $pdo->prepare("SELECT * FROM forum_replies WHERE id = ? AND user_id = ?");
    $stmt->execute([$editingReplyId, $userId]);
    $editingReply = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($thread['title']) ?> - Forum</title>
    <link rel="stylesheet" href="../styles/thread.css">
</head>
<body>
    <h1><?= htmlspecialchars($thread['title']) ?></h1>
    <p><?= nl2br(htmlspecialchars($thread['content'])) ?></p>
    <small>Posted by <?= htmlspecialchars($thread['name']) ?> on <?= $thread['created_at'] ?></small>

    <hr>
    <h2>Replies</h2>
    <?php foreach ($replies as $reply): ?>
        <div style="margin-bottom:10px; border:1px solid #ccc; padding:10px;">
            <p><?= nl2br(htmlspecialchars($reply['content'])) ?></p>
            <small>By <?= htmlspecialchars($reply['name']) ?> on <?= $reply['created_at'] ?></small>

            <?php if ($userId && $reply['user_id'] == $userId): ?>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this reply?');">
                    <input type="hidden" name="delete_reply_id" value="<?= $reply['id'] ?>">
                    <button type="submit">Delete</button>
                </form>
                <a href="thread.php?id=<?= $threadId ?>&edit_reply=<?= $reply['id'] ?>" style="margin-left:10px;">Edit</a>
            <?php endif; ?>
        </div>  
    <?php endforeach; ?>

    <?php if ($userId): ?>
        <h3><?= $editingReply ? 'Edit Reply' : 'Post a Reply' ?></h3>
        <form method="POST">
            <?php if ($editingReply): ?>
                <input type="hidden" name="reply_id" value="<?= $editingReply['id'] ?>">
            <?php endif; ?>
            <textarea name="content" required><?= $editingReply ? htmlspecialchars($editingReply['content']) : '' ?></textarea><br>
            <button type="submit"><?= $editingReply ? 'Update Reply' : 'Reply' ?></button>
            <?php if ($editingReply): ?>
                <a href="thread.php?id=<?= $threadId ?>">Cancel</a>
            <?php endif; ?>
        </form>
    <?php else: ?>
        <p><a href="../login.php">Log in</a> to reply.</p>
    <?php endif; ?>
</body>
</html>