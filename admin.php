<?php
session_start();
include 'db.php';

// GATEKEEPER
if (!isset($_SESSION['username']) || strtolower(trim($_SESSION['role'])) !== 'admin') {
    header("Location: login.php?err=no_access");
    exit();
}

// DELETE LOGIC
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM users WHERE id = $id AND role != 'admin'");
    header("Location: admin.php?msg=Student Deleted");
    exit();
}

// 1. Fetch Stats
$total_students = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'student'")->fetch_assoc()['count'];
$total_questions = $conn->query("SELECT COUNT(*) as count FROM questions")->fetch_assoc()['count'];
$avg_score = $conn->query("SELECT AVG(score) as avg FROM results")->fetch_assoc()['avg'] ?? 0;

// 2. Chart Data: Name vs Attempts vs Total Points
$chart_query = $conn->query("SELECT username, COUNT(*) as attempts, SUM(score) as total_score 
                             FROM results 
                             GROUP BY username 
                             ORDER BY total_score DESC LIMIT 10");

$names = []; $attempts = []; $scores = [];
while($row = $chart_query->fetch_assoc()) {
    $names[] = $row['username'];
    $attempts[] = $row['attempts'];
    $scores[] = $row['total_score'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Admin Dashboard </title>
</head>
<body>

<div class="sidebar">
    <h2 class="cinematic-text">Admin</h2>
    <div class="sidebar-menu">
        <a href="admin.php" class="side-btn active"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
        <a href="add_questions.php" class="side-btn"><i class="fa-solid fa-plus"></i> Add Question</a>
        <a href="manage_questions.php" class="side-btn"><i class="fa-solid fa-list-check"></i> Manage Qs</a>
    </div>
    <div class="logout-box"><a href="logout.php" class="logout-btn">Logout</a></div>
</div>

<div class="main-content">
    <div class="dashboard-header">
        <h1 class="cinematic-text">Analytics Overview</h1>
    </div>

    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
        <div class="card" style="margin:0; border-left: 4px solid #3b82f6;">
            <p style="color: var(--text-muted);">Total Students</p>
            <h2><?php echo $total_students; ?></h2>
        </div>
        <div class="card" style="margin:0; border-left: 4px solid #10b981;">
            <p style="color: var(--text-muted);">Questions</p>
            <h2><?php echo $total_questions; ?></h2>
        </div>
        <div class="card" style="margin:0; border-left: 4px solid #fbbf24;">
            <p style="color: var(--text-muted);">Avg. Points</p>
            <h2><?php echo number_format($avg_score, 1); ?></h2>
        </div>
    </div>

    <div class="card" style="max-width: 100%; margin-bottom: 2rem;">
        <h3><i class="fa-solid fa-chart-column"></i> Performance & Activity</h3>
        <div style="height: 400px;"><canvas id="performanceChart"></canvas></div>
    </div>
</div>

<script>
const ctx = document.getElementById('performanceChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($names); ?>,
        datasets: [
            {
                label: 'Total Points',
                data: <?php echo json_encode($scores); ?>,
                backgroundColor: '#3b82f6',
                borderRadius: 5
            },
            {
                label: 'Attempts',
                data: <?php echo json_encode($attempts); ?>,
                backgroundColor: 'rgba(168, 85, 247, 0.4)',
                borderRadius: 5
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { color: '#94a3b8' } } },
        scales: {
            y: { grid: { color: 'rgba(255, 255, 255, 0.05)' }, ticks: { color: '#94a3b8' } },
            x: { ticks: { color: '#94a3b8' } }
        }
    }
});
</script>
</body>
</html>