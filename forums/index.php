<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Optional: Define root path once if you're including other shared files
define('ROOT_PATH', dirname(__DIR__));

// Assuming $threads is already passed from the controller
// Example: $threads = ForumThread::all();

// Use __DIR__ to reliably include shared files
include ROOT_PATH . '/views/shared/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Community Forum</title>
    <link rel="stylesheet" href="/public/css/forum.css"> <!-- Link to external styles -->
</head>
<body>

<div class="forum-container">
    <h1>Community Forum</h1>

    <div>
        <a href="/forum/new" class="btn btn-primary">+ Start New Thread</a>
    </div>

    <?php if (!empty($threads)): ?>
        <?php foreach ($threads as $thread): ?>
            <div class="thread-preview">
                <h3>
                    <a href="/forum/thread/<?php echo $thread['id']; ?>">
                        <?php echo htmlspecialchars($thread['title']); ?>
                    </a>
                </h3>
                <p>
                    Posted by User #<?php echo $thread['user_id']; ?>
                    on <?php echo date('F j, Y, g:i a', strtotime($thread['created_at'])); ?>
                </p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No threads yet. <a href="/forum/new">Start one now</a>!</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
// Footer include
include ROOT_PATH . '/views/shared/footer.php';
?>
