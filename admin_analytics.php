<?php
session_start();
include 'db.php';

// --- GATEKEEPER: Ensure only Admins can enter ---
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?err=Admin access required");
    exit();
}

// 1. Fetch Global Stats
$total_attempts = $conn->query("SELECT COUNT(*) as count FROM results")->fetch_assoc()['count'];
$avg_score = $conn->query("SELECT AVG(score) as avg FROM results")->fetch_assoc()['avg'];
$top_performer = $conn->query("SELECT username, MAX(score) as high FROM results GROUP BY username ORDER BY high DESC LIMIT 1")->fetch_assoc();

// 2. Fetch All Results for the Data Table
$all_results = $conn->query("SELECT * FROM results ORDER BY date_taken DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Admin Analytics | Dream AI</title>
</head>
<body>

<div class="sidebar">
    <h2 class="cinematic-text"><i class="fa-solid fa-shield-halved"></i> Admin Portal</h2>
    <div class="sidebar-menu">
        <a href="admin_dashboard.php" class="side-btn"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
        <a href="manage_questions.php" class="side-btn"><i class="fa-solid fa-list-check"></i> Questions</a>
        <a href="admin_analytics.php" class="side-btn active"><i class="fa-solid fa-database"></i> Analytics</a>
    </div>
    <div class="logout-box">
        <a href="logout.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="dashboard-header">
        <h1 class="cinematic-text">Performance Analytics</h1>
        <p style="color: var(--text-muted);">Real-time overview of student assessments and scores.</p>
    </div>

    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin: 30px 0;">
        <div class="card" style="margin: 0; border-top: 3px solid var(--primary);">
            <p style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Total Attempts</p>
            <h2 style="font-size: 2.5rem; margin-top: 10px;"><?php echo $total_attempts; ?></h2>
        </div>
        <div class="card" style="margin: 0; border-top: 3px solid #10b981;">
            <p style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Avg. Score</p>
            <h2 style="font-size: 2.5rem; margin-top: 10px;"><?php echo number_format($avg_score, 1); ?></h2>
        </div>
        <div class="card" style="margin: 0; border-top: 3px solid #fbbf24;">
            <p style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Top Student</p>
            <h2 style="font-size: 1.5rem; margin-top: 15px; color: #fff;">
                <?php echo $top_performer ? htmlspecialchars($top_performer['username']) : 'N/A'; ?>
            </h2>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-clock-rotate-left"></i> Assessment Logs</h3>
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Score (Raw)</th>
                    <th>Total Qs</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $all_results->fetch_assoc()): ?>
                <tr>
                    <td style="font-weight: 600;"><?php echo htmlspecialchars($row['username']); ?></td>
                    <td style="color: var(--primary); font-weight: bold; font-size: 1.1rem;">
                        <?php echo $row['score']; ?>
                    </td>
                    <td><?php echo $row['total_questions']; ?></td>
                    <td style="color: var(--text-muted); font-size: 0.9rem;">
                        <?php echo date('M d, Y | H:i', strtotime($row['date_taken'])); ?>
                    </td>
                    <td>
                        <?php 
                        // Visual indicator for pass/fail (e.g., if score is > 50% of total)
                        if ($row['score'] >= ($row['total_questions'] / 2)) {
                            echo '<span style="color: #10b981;"><i class="fa-solid fa-circle-check"></i> Pass</span>';
                        } else {
                            echo '<span style="color: #ef4444;"><i class="fa-solid fa-circle-xmark"></i> Review</span>';
                        }
                        ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>