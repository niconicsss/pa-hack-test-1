<?php
session_start();
require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$editThread = null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // New thread
    if (isset($_POST['title'], $_POST['content']) && empty($_POST['thread_id'])) {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        if ($title && $content) {
            $stmt = $pdo->prepare("INSERT INTO forum_threads (user_id, title, content) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $title, $content]);
            header('Location: index.php');
            exit;
        }
    }

    // Edit thread
    if (isset($_POST['thread_id'], $_POST['title'], $_POST['content'])) {
        $threadId = $_POST['thread_id'];
        $stmt = $pdo->prepare("SELECT * FROM forum_threads WHERE id = ? AND user_id = ?");
        $stmt->execute([$threadId, $userId]);
        $thread = $stmt->fetch();

        if ($thread) {
            $stmt = $pdo->prepare("UPDATE forum_threads SET title = ?, content = ? WHERE id = ?");
            $stmt->execute([trim($_POST['title']), trim($_POST['content']), $threadId]);
        }

        header('Location: index.php');
        exit;
    }

    // Delete thread
    if (isset($_POST['delete_thread_id'])) {
        $threadId = $_POST['delete_thread_id'];
        $stmt = $pdo->prepare("SELECT * FROM forum_threads WHERE id = ? AND user_id = ?");
        $stmt->execute([$threadId, $userId]);
        $thread = $stmt->fetch();

        if ($thread) {
            $stmt = $pdo->prepare("DELETE FROM forum_threads WHERE id = ?");
            $stmt->execute([$threadId]);
        }

        header('Location: index.php');
        exit;
    }
}

// Handle edit request (via GET)
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM forum_threads WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['edit'], $userId]);
    $editThread = $stmt->fetch();
}

// Fetch threads
$threads = $pdo->query("SELECT forum_threads.*, users.name FROM forum_threads 
                        JOIN users ON forum_threads.user_id = users.id 
                        ORDER BY forum_threads.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forum</title>
</head>
<body>

<h2><?= $editThread ? 'Edit Thread' : 'Start a New Thread' ?></h2>
<form method="POST">
    <?php if ($editThread): ?>
        <input type="hidden" name="thread_id" value="<?= $editThread['id'] ?>">
    <?php endif; ?>
    <input type="text" name="title" placeholder="Thread title" required value="<?= $editThread ? htmlspecialchars($editThread['title']) : '' ?>"><br>
    <textarea name="content" placeholder="What's on your mind?" required><?= $editThread ? htmlspecialchars($editThread['content']) : '' ?></textarea><br>
    <button type="submit"><?= $editThread ? 'Update Thread' : 'Post Thread' ?></button>
    <?php if ($editThread): ?>
        <a href="index.php">Cancel</a>
    <?php endif; ?>
</form>

<h2>Recent Discussions</h2>
<?php foreach ($threads as $thread): ?>
    <div style="border:1px solid #ccc; margin-bottom:10px; padding:10px;">
        <h3><a href="thread.php?id=<?= $thread['id'] ?>"><?= htmlspecialchars($thread['title']) ?></a></h3>
        <p><?= nl2br(htmlspecialchars(substr($thread['content'], 0, 150))) ?>...</p>
        <small>Posted by <?= htmlspecialchars($thread['name']) ?> on <?= $thread['created_at'] ?></small>

        <?php if ($thread['user_id'] == $userId): ?>
            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this thread?');" style="margin-top:5px; display:inline;">
                <input type="hidden" name="delete_thread_id" value="<?= $thread['id'] ?>">
                <button type="submit" style="color:red;">Delete</button>
            </form>
            <a href="index.php?edit=<?= $thread['id'] ?>" style="margin-left:10px;">Edit</a>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

</body>
</html>