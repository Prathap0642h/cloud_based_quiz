<?php
session_start();
include 'db.php';

// --- GATEKEEPER: Fixed to use 'username' ---
if (!isset($_SESSION['username'])) { 
    header("Location: login.php"); 
    exit(); 
}

// Fetching Top 10 High Scores
$res = $conn->query("SELECT username, MAX(score) as top FROM results GROUP BY username ORDER BY top DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Leaderboard | Dream AI</title>
</head>
<body>
    <div class="sidebar">
        <h2 class="cinematic-text"><i class="fa-solid fa-wand-magic-sparkles"></i> Dream AI</h2>
        <div class="sidebar-menu">
            <a href="student_dashboard.php" class="side-btn"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="quiz.php" class="side-btn"><i class="fa-solid fa-pen-to-square"></i> Take Quiz</a>
            <a href="leaderboard.php" class="side-btn active"><i class="fa-solid fa-trophy"></i> Leaderboard</a>
        </div>
        <div class="logout-box">
            <a href="logout.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="card" style="max-width: 1000px; margin: auto;">
            <h1 class="cinematic-text"><i class="fa-solid fa-crown"></i> Global Rankings</h1>
            <p style="color: var(--text-muted); margin-bottom: 25px;">Top performance scores across the platform.</p>
            
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Student Name</th>
                        <th style="text-align: right;">Highest Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1; 
                    while($row = $res->fetch_assoc()): 
                        // Highlight the current user in the list
                        $highlight = ($row['username'] === $_SESSION['username']) ? 'style="background: rgba(59, 130, 246, 0.1);"' : '';
                    ?>
                    <tr <?php echo $highlight; ?>>
                        <td style="font-weight: 800; color: <?php echo ($rank <= 3) ? '#fbbf24' : 'inherit'; ?>;">
                            #<?php echo $rank++; ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['username']); ?>
                            <?php if($row['username'] === $_SESSION['username']) echo " <small>(You)</small>"; ?>
                        </td>
                        <td style="text-align: right; color: var(--primary); font-weight: bold; font-size: 1.2rem;">
                            <?php echo $row['top']; ?> 
                            <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: normal;">pts</span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>