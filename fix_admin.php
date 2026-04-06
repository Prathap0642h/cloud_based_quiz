<?php
include 'db.php';

// We are fixing the 'admin' user specifically
$username = 'admin';
$password = 'admin123';

// This generates a fresh hash using your server's local algorithm
$new_hash = password_hash($password, PASSWORD_DEFAULT);

// Update the database with the fresh hash
$stmt = $conn->prepare("UPDATE users SET password = ?, role = 'admin' WHERE username = ?");
$stmt->bind_param("ss", $new_hash, $username);

if ($stmt->execute()) {
    echo "<body style='background:#0f172a; color:#22c55e; font-family:sans-serif; padding:50px;'>";
    echo "<h1>✅ Admin Account Fixed!</h1>";
    echo "<p style='color:#f8fafc;'>Your server successfully generated and saved a new hash.</p>";
    echo "<p style='background:#1e293b; padding:10px; border-radius:5px; color:#3b82f6;'>New Hash: $new_hash</p>";
    echo "<a href='login.php' style='color:#3b82f6; text-decoration:none;'>Click here to Login with <b>admin123</b></a>";
    echo "</body>";
} else {
    echo "❌ Error: " . $conn->error;
}
?>