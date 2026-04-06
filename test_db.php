<?php
// --- THE SYNCED CONNECTION ---
$host = "sql100.infinityfree.com"; 
$user = "if0_41490750";            
$pass = "BcaProject2026!"; // The new password you just set
$dbname = "if0_41490750_quizdb";   

mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);

try {
    // We trim to remove any accidental whitespace
    $conn = new mysqli($host, $user, trim($pass), $dbname);
    echo "<h1 style='color: #10b981;'>Handshake Accepted!</h1>";
    echo "<p>Your project is now connected to the database.</p>";
} catch (mysqli_sql_exception $e) {
    echo "<h1 style='color: #ef4444;'>Sync Still Pending</h1>";
    echo "Message: " . $e->getMessage();
}
?>