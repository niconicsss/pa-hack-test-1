<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

require '../config/db.php';  // Database connection

$user_id = $_SESSION['user_id'];

// Fetch companies from the database
$stmt = $pdo->query("SELECT id, name FROM companies");
$companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set the default company to 'Gothong Southern' (company_id = 6)
$selected_company = isset($_GET['company']) ? (int) $_GET['company'] : 6; // Default to company_id = 6

// Fetch courses based on selected company, or all companies if no company is selected
if ($selected_company > 0) {
    $stmt = $pdo->prepare("
        SELECT c.* 
        FROM courses c
        JOIN company_courses cc ON c.id = cc.course_id
        WHERE cc.company_id = ?
    ");
    $stmt->execute([$selected_company]);
} else {
    $stmt = $pdo->query("SELECT * FROM courses");
}
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user's course progress
$progress_stmt = $pdo->prepare("SELECT course_id, status FROM progress WHERE user_id = ?");
$progress_stmt->execute([$user_id]);
$progress_data = $progress_stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Library</title>
    <link rel="stylesheet" href="../styles/courses.css">
</head>
<body>
    <h1>📚 Course Library</h1>
    <p>Learn essential digital tools and logistics practices to stay competitive in the industry.</p>

    <!-- Dropdown to select company -->
    <form method="GET">
        <label for="company">Choose a Company:</label>
        <select name="company" id="company" onchange="this.form.submit()">
            <option value="">-- All Companies --</option>
            <?php foreach ($companies as $company): ?>
                <option value="<?= $company['id'] ?>" <?= $selected_company === (int) $company['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($company['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <div class="course-list">
        <?php if (empty($courses)): ?>
            <p>No courses found for the selected company.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($courses as $course): ?>
                    <li class="course-item">
                        <h2><?= htmlspecialchars($course['title']) ?></h2>
                        <p><?= htmlspecialchars($course['description']) ?></p>
                        <p><strong>Level:</strong> <?= ucfirst($course['level']) ?></p>
                        <p><strong>Status:</strong>
                            <?= htmlspecialchars($progress_data[$course['id']] ?? 'Not Started') ?>
                        </p>
                        <a class="btn" href="view.php?id=<?= $course['id'] ?>">Start Learning ➜</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <p><a href="../dashboard.php">← Back to Dashboard</a></p>
</body>
</html>
