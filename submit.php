<?php
session_start();
include 'db.php';

// Check for POST and session
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $raw_points = 0;
    $total = 0;

    // 1. Grade the quiz
    foreach ($_POST as $key => $val) {
        if (strpos($key, 'q') === 0) {
            $total++;
            $id = substr($key, 1);
            
            $stmt_check = $conn->prepare("SELECT correct FROM questions WHERE id = ?");
            $stmt_check->bind_param("i", $id);
            $stmt_check->execute();
            $res = $stmt_check->get_result();
            
            if ($row = $res->fetch_assoc()) {
                if (trim(strtolower($val)) === trim(strtolower($row['correct']))) {
                    $raw_points++;
                }
            }
        }
    }

    $final_score = $raw_points; 

    
	$sql = "INSERT INTO results (username, score, total_questions, date_taken) VALUES (?, ?, ?, NOW())";
	$stmt = $conn->prepare($sql);


	$stmt->bind_param("sii", $username, $final_score, $total);

	if (!$stmt->execute()) {
    	die("Database Error: " . $stmt->error);
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Results | Dream AI</title>
</head>
<body class="auth-page">
    <div class="card" style="text-align: center; max-width: 500px; margin: 50px auto;">
        <h1 class="cinematic-text">Assessment Complete</h1>
        <div style="margin: 40px 0;">
            <div style="color: var(--primary); font-weight: 700;">SCORE</div>
            <div style="font-size: 6rem; font-weight: 900; color: #fff;">
                <?php echo $final_score; ?>
            </div>
            <p style="color: var(--text-muted);">Points earned out of <?php echo $total; ?></p>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <a href="student_dashboard.php" class="side-btn" style="text-align:center;">Dashboard</a>
            <a href="quiz.php" class="btn">Retake</a>
        </div>
    </div>
</body>
</html>
<?php 
} else {
    header("Location: login.php");
    exit();
} 
?>