<?php
// --- FINAL ATTEMPT DATABASE CONNECTION ---
$host = "sql100.infinityfree.com"; 
$user = "if0_41490750";            
$pass = "BcaProject2026"; // This must be exactly what is in your panel
$dbname = "if0_41490750_quizdb";   

// Using mysqli with error reporting enabled
mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);

try {
    $conn = new mysqli($host, $user, trim($pass), $dbname);
    // If we reach here, it worked!
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    // This will tell us if it's a password issue or a host issue
    die("Database Error: " . $e->getMessage());
}
?>