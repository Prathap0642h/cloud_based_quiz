<?php
session_start();
include 'db.php';

// 1. Delete Logic
if(isset($_GET['delete_id'])){
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM questions WHERE id = $id");
    header("Location: admin_manage.php?msg=Deleted");
}

// 2. Fetch Questions
$all_q = $conn->query("SELECT * FROM questions ORDER BY id DESC");

// 3. Fetch Performance Data for Chart
$perf = $conn->query("SELECT username, score FROM results ORDER BY date_taken DESC LIMIT 10");
$chart_users = [];
$chart_scores = [];
while($r = $perf->fetch_assoc()){
    $chart_users[] = $r['username'];
    $chart_scores[] = $r['score'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Admin Management</title>
</head>
<body>
    <div class="card" style="max-width: 900px;">
        <h1>System Management</h1>
        
        <h3>Student Performance (Last 10 Quizzes)</h3>
        <div class="chart-container">
            <canvas id="performanceChart"></canvas>
        </div>

        <hr style="border:0; border-top:1px solid var(--glass-border); margin:40px 0;">

        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h3>Question Bank</h3>
            <a href="add_question.php" class="btn" style="width:auto;">+ Add New</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Correct</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($q = $all_q->fetch_assoc()): ?>
                <tr>
                    <td style="font-size:0.9rem;"><?php echo htmlspecialchars($q['question']); ?></td>
                    <td style="color:#22c55e; font-weight:bold;"><?php echo strtoupper($q['correct']); ?></td>
                    <td>
                        <a href="admin_manage.php?delete_id=<?php echo $q['id']; ?>" 
                           class="btn-danger" onclick="return confirm('Delete this question?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        const ctx = document.getElementById('performanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_reverse($chart_users)); ?>,
                datasets: [{
                    label: 'Score Percentage',
                    data: <?php echo json_encode(array_reverse($chart_scores)); ?>,
                    backgroundColor: '#3b82f6',
                    borderRadius: 5
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, max: 100, ticks: { color: '#94a3b8' } },
                    x: { ticks: { color: '#94a3b8' } }
                },
                plugins: { legend: { display: false } }
            }
        });
    </script>
</body>
</html>