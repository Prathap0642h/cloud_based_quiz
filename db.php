<?php
// On Vercel, we use getenv() to pull secrets safely
$host = getenv('DB_HOST') ?: "sql100.infinityfree.com"; 
$user = getenv('DB_USER') ?: "if0_41490750"; 
$pass = getenv('DB_PASS') ?: "BcaProject2026"; 
$dbname = getenv('DB_NAME') ?: "if0_41490750_quizdb";   

mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);

try {
    $conn = new mysqli($host, $user, trim($pass), $dbname);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    // On production, don't show the full error to users, but keep it for debugging now
    die("Database Connection Failed. Check if your host allows external connections.");
}
?>
