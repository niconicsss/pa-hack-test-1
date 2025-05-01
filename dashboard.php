<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require 'config/db.php';
$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SkillCargo Dashboard</title>
  <style>
    /* Resetting default margins and paddings */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    /* Body styling */
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #FEF9E1;
        color: #000000;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 100vh;
    }

    /* Top Bar Styling */
    .logout-container {
        width: 100%;
        background-color: #A31D1D;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 2rem;
    }

    .logout-container span {
        font-size: 1.2rem;
        font-weight: bold;
    }

    .logout-container a {
        color: #fff;
        text-decoration: none;
        font-size: 1rem;
        font-weight: bold;
        transition: color 0.3s;
    }

    .logout-container a:hover {
        color: #E5D0AC;
    }

    /* Main title styling */
    h1 {
        text-align: center;
        font-size: 3rem;
        margin-top: 3rem;
        color: #6D2323;
    }

    /* Dashboard Section Styling */
    .dashboard-sections {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        padding: 2rem;
        max-width: 1200px;
        width: 100%;
    }

    section {
        background-color: #E5D0AC;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    section:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    section h2 {
        font-size: 1.8rem;
        color: #A31D1D;
        margin-bottom: 1rem;
    }

    section p {
        font-size: 1rem;
        color: #333;
        margin-bottom: 1.5rem;
    }

    section a {
        font-size: 1.1rem;
        color: #6D2323;
        text-decoration: none;
        font-weight: bold;
        transition: color 0.3s ease;
    }

    section a:hover {
        color: #A31D1D;
    }

    /* === Slideshow Styles === */
    .slideshow {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -2;
        overflow: hidden;
    }

    .slide {
        position: absolute;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        opacity: 0;
        animation: slideShow 36s infinite;
    }

    .slide:nth-child(1) { background-image: url('image/2g0.jpg'); animation-delay: 0s; }
    .slide:nth-child(2) { background-image: url('image/airspeeed.jpg'); animation-delay: 6s; }
    .slide:nth-child(3) { background-image: url('image/f2.jpg'); animation-delay: 12s; }
    .slide:nth-child(4) { background-image: url('image/gothong.png'); animation-delay: 18s; }
    .slide:nth-child(5) { background-image: url('image/jrs.jpg'); animation-delay: 24s; }
    .slide:nth-child(6) { background-image: url('image/lbcc.jpg'); animation-delay: 30s; }

    @keyframes slideShow {
        0%   { opacity: 0; }
        8%   { opacity: 1; }
        25%  { opacity: 1; }
        33%  { opacity: 0; }
        100% { opacity: 0; }
    }

    /* Optional overlay for readability */
    .slideshow-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.4); /* light overlay */
        z-index: -1;
    }
  </style>
</head>
<body>

  <!-- Slideshow Background -->
  <div class="slideshow">
    <div class="slide"></div>
    <div class="slide"></div>
    <div class="slide"></div>
    <div class="slide"></div>
    <div class="slide"></div>
    <div class="slide"></div>
  </div>
  <div class="slideshow-overlay"></div>

  <!-- Top Bar -->
  <div class="logout-container">
  <img src="image/SKILOGO.png" alt="SkillCargo" class="logo">
    <a href="logout.php">Logout</a>
  </div>

  <!-- Page Title -->
  <h1>📦 Skill Cargo </h1>

  <!-- Dashboard Sections -->
  <div class="dashboard-sections">
    <section>
      <h2>📚 Course Library</h2>
      <p>Learn essential digital tools and logistics practices to stay competitive.</p>
      <a href="courses/index.php">Access Courses</a>
    </section>

    <section>
      <h2>📈 Progress Tracking</h2>
      <p>Track your learning journey and monitor your improvement over time.</p>
      <a href="progress/index2.php">View Progress</a>
    </section>

    <section>
      <h2>🧠 Interactive Quizzes</h2>
      <p>Test your knowledge and reinforce what you've learned with quick assessments.</p>
      <a href="quizes/index2.php">Take a Quiz</a>
    </section>

    <section>
      <h2>👥 Community Forum</h2>
      <p>Connect with fellow workers, ask questions, and share logistics tips.</p>
      <a href="forums/index.php">Join the Discussion</a>
    </section>
  </div>

</body>
</html>
