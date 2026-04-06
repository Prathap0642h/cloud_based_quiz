<?php 
session_start(); 
$s = $_GET['s'] ?? 0; 
$t = $_GET['t'] ?? 0; 
$percentage = ($t > 0) ? ($s / $t) * 100 : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Assessment Complete</title>
</head>
<body>
    <div class="card" style="text-align:center; max-width: 500px; padding: 4rem 2rem;">
        <h2 style="color: var(--text-muted); font-size: 1.2rem; text-transform: uppercase; letter-spacing: 2px;">
            Assessment Complete
        </h2>
        
        <div style="font-size: 5rem; font-weight: 800; margin: 20px 0; background: linear-gradient(to right, #3b82f6, #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            <?php echo $s; ?> / <?php echo $t; ?>
        </div>
        
        <div style="width: 100%; background: rgba(255,255,255,0.1); border-radius: 10px; height: 10px; margin-bottom: 30px; overflow: hidden;">
            <div style="width: <?php echo $percentage; ?>%; background: var(--primary); height: 100%; transition: width 1.5s ease-out;"></div>
        </div>

        <a href="index.php" class="btn" style="text-decoration:none;">Return to Dashboard</a>
    </div>
</body>
</html>