<?php
session_start();
include 'db.php';

// GATEKEEPER
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = $_POST['question'];
    $a = $_POST['option_a'];
    $b = $_POST['option_b'];
    $c = $_POST['option_c'];
    $d = $_POST['option_d'];
    $correct = $_POST['correct'];

    $stmt = $conn->prepare("INSERT INTO questions (question, option_a, option_b, option_c, option_d, correct) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $question, $a, $b, $c, $d, $correct);

    if ($stmt->execute()) {
        $msg = "Question Added Successfully!";
    } else {
        $msg = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Add Question | Dream AI</title>
</head>
<body>
    <div class="sidebar">
        <h2 class="cinematic-text"><i class="fa-solid fa-wand-magic-sparkles"></i> Admin</h2>
        <div class="sidebar-menu">
            <a href="admin.php" class="side-btn"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="add_questions.php" class="side-btn active"><i class="fa-solid fa-plus"></i> Add Question</a>
            <a href="manage_questions.php" class="side-btn"><i class="fa-solid fa-list-check"></i> Manage Qs</a>
        </div>
    </div>

    <div class="main-content">
        <div class="card" style="max-width: 600px; margin: auto;">
            <h1 class="cinematic-text">New Assessment Item</h1>
            <?php if($msg) echo "<p style='color: #10b981; margin-bottom: 20px;'>$msg</p>"; ?>
            
            <form method="POST">
                <div class="input-group" style="margin-bottom: 15px;">
                    <label style="color: var(--text-muted); display: block; margin-bottom: 5px;">Question Text</label>
                    <textarea name="question" required style="width: 100%; padding: 10px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); color: #fff; border-radius: 8px;"></textarea>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <input type="text" name="option_a" placeholder="Option A" required class="input-field">
                    <input type="text" name="option_b" placeholder="Option B" required class="input-field">
                    <input type="text" name="option_c" placeholder="Option C" required class="input-field">
                    <input type="text" name="option_d" placeholder="Option D" required class="input-field">
                </div>

                <div class="input-group" style="margin-bottom: 25px;">
                    <label style="color: var(--text-muted);">Correct Answer</label>
                    <select name="correct" required style="width: 100%; padding: 10px; background: #111; color: #fff; border: 1px solid var(--glass-border); border-radius: 8px;">
                        <option value="a">Option A</option>
                        <option value="b">Option B</option>
                        <option value="c">Option C</option>
                        <option value="d">Option D</option>
                    </select>
                </div>

                <button type="submit" class="btn" style="width: 100%;"><i class="fa-solid fa-cloud-arrow-up"></i> Save to Question Bank</button>
            </form>
        </div>
    </div>
</body>
</html>