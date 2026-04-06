<?php session_start(); if(!isset($_SESSION['user'])) header("Location: login.php"); ?>
<!DOCTYPE html><html><head><link rel="stylesheet" href="style.css"></head><body>
<div class="card" style="text-align:center;">
    <h1>Welcome, <?php echo $_SESSION['user']; ?></h1>
    <p>Please select an action below to proceed.</p>
    <a href="quiz.php" class="btn">Start New Quiz</a><br><br>
    <a href="logout.php" style="color:#94a3b8">Logout</a>
</div></body></html>