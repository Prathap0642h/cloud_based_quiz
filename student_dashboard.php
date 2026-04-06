<?php
session_start();
include('db.php'); 

// GATEKEEPER: Ensures the user is logged in as a student
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php?err=Please login to access your dashboard");
    exit();
}

$current_user = $_SESSION['username']; 

// 1. Fetch Student Stats (Total Points & Quiz Count)
$stmt = $conn->prepare("SELECT COUNT(*) as quizzes_taken, SUM(score) as total_points FROM results WHERE username = ?");
$stmt->bind_param("s", $current_user);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();

$quizzes = $stats['quizzes_taken'] ?? 0;
$points = $stats['total_points'] ?? 0;

// 2. Fetch Latest Results for the table
$stmt = $conn->prepare("SELECT score, total_questions, date_taken FROM results WHERE username = ? ORDER BY date_taken DESC LIMIT 5");
$stmt->bind_param("s", $current_user);
$stmt->execute();
$history = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Progress</title>
</head>
<body>

<div class="sidebar">
    <h2>Dashboard</h2>
    <div class="sidebar-menu">
        <a href="student_dashboard.php" class="side-btn active">Dashboard</a>
        <a href="quiz.php" class="side-btn">Start Quiz</a>
    </div>
    <div class="logout-box">
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<div class="main-content">
    <h1 class="cinematic-text">Welcome, <?php echo htmlspecialchars($current_user); ?></h1>
    
    <div class="stats-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 30px 0;">
        <div class="card" style="margin: 0; border-left: 4px solid var(--primary);">
            <p style="color: var(--text-muted);">Quizzes Taken</p>
            <h2><?php echo $quizzes; ?></h2>
        </div>
        <div class="card" style="margin: 0; border-left: 4px solid #10b981;">
            <p style="color: var(--text-muted);">Total Points</p>
            <h2><?php echo $points; ?></h2>
        </div>
    </div>

    <div class="card">
        <h3>Recent Performance</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Raw Score</th>
                    <th>Questions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $history->fetch_assoc()): ?>
                <tr>
                    <td><?php echo date('M d, Y', strtotime($row['date_taken'])); ?></td>
                    <td style="color: var(--primary); font-weight: bold;"><?php echo $row['score']; ?></td>
                    <td><?php echo $row['total_questions']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>