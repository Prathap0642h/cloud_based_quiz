<?php
include 'db.php';

if (isset($_POST['register'])) {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);
    $role = 'student'; // Default role

    // 1. Check if username exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE LOWER(username) = LOWER(?)");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Redirect back with an error
        header("Location: register.php?error=taken");
        exit();
    } else {
        // 2. Generate Hash on YOUR server (The Final Fix)
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

        // 3. Insert new user
        $insert = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $user, $hashed_pass, $role);
        
        if ($insert->execute()) {
            // Success! Send to login
            header("Location: login.php?msg=Registration successful! Please login.");
        } else {
            header("Location: register.php?error=server");
        }
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
?>