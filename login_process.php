<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = $_POST['password'];

    // Fetch user data
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];

            // CRITICAL: Check the role string exactly as it is in your DB
            if (strtolower($user['role']) === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                // If the dashboard isn't working, try redirecting directly to quiz.php
                header("Location: student_dashboard.php"); 
            }
            exit();
        } else {
            header("Location: login.php?error=invalid");
            exit();
        }
    } else {
        header("Location: login.php?error=notfound");
        exit();
    }
}
?>