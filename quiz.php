<?php
session_start();
include 'db.php';

// --- GATEKEEPER ---
// Fixed: Changed 'user' to 'username' to match login.php
if (!isset($_SESSION['username'])) { 
    header("Location: login.php?err=Please login first"); 
    exit(); 
}

// Fetch 10 random questions
$query = "SELECT * FROM questions ORDER BY RAND() LIMIT 10";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Live Assessment | Dream AI</title>
</head>
<body class="quiz-page">
    <div class="card">
        <h1 class="cinematic-text" style="margin-bottom: 30px;">Live Assessment</h1>
        
        <form action="submit.php" method="POST">
            <?php 
            $count = 1;
            while($q = $result->fetch_assoc()): 
            ?>
                <div class="question-block" style="margin-bottom: 25px;">
                    <p style="font-size: 1.2rem; margin-bottom: 20px; font-weight: 500;">
                        <span class="question-number"><?php echo $count; ?>.</span> 
                        <?php echo htmlspecialchars($q['question']); ?>
                    </p>

                    <div class="options-group">
                        <?php foreach(['a', 'b', 'c', 'd'] as $opt): ?>
    							<label class="option-container">
        							<input type="radio" name="q<?php echo $q['id']; ?>" value="<?php echo $opt; ?>" required>
        
        							<span class="checkmark"></span>
        
        							<span class="option-text"><?php echo htmlspecialchars($q[$opt]); ?></span>
    						</label>
						<?php endforeach; ?>
                    </div>
                </div>
            <?php 
                $count++;
            endwhile; 
            ?>

            <input type="hidden" name="total_questions" value="<?php echo ($count - 1); ?>">

            <button type="submit" class="btn" style="margin-top: 20px; font-size: 1.1rem; letter-spacing: 1px; width: 100%;">
                Submit Assessment
            </button>
        </form>
    </div>
</body>
</html>