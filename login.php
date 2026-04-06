<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();
session_start();
include 'db.php';

$err = "";

if (isset($_POST['login'])) {
    // 1. Clean inputs - Using 'username' and 'password' to match the updated HTML below
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']); 

    // 2. Fixed: Uses $user in bind_param to match the variable defined above
    $stmt = $conn->prepare("SELECT * FROM users WHERE LOWER(username) = LOWER(?) LIMIT 1");
    $stmt->bind_param("s", $user); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // 3. Verify typed password against server-side hash
        if (password_verify($pass, $row['password'])) {
            
            // 4. Set session variables - Using 'username' to match your submit.php check
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = strtolower(trim($row['role']));
            
            // 5. Role-based redirection
            if ($_SESSION['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: student_dashboard.php");
            }
            exit();
        } else {
            $err = "Invalid password. Please try again.";
        }
    } else {
        $err = "Username not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login | Dream AI</title>
</head>
<body>
    <div class="card">
        <h1 class="cinematic-text" style="text-align: center; margin-bottom: 15px;"> project assessment Login</h1>

        <?php if (!empty($err)) echo '<div class="alert-error" style="color:#ef4444; text-align:center; margin-bottom:10px;">' . $err . '</div>'; ?>
        <?php if (isset($_GET['msg'])) echo '<div class="alert-success" style="color:#22c55e; text-align:center; margin-bottom:10px;">' . htmlspecialchars($_GET['msg']) . '</div>'; ?>

        <form action="login.php" method="POST" id="loginForm" autocomplete="off">
            
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter Username" required>
            </div>
            
            <div class="input-group" style="position: relative; margin-bottom: 25px;">
                <label>Password</label>
                <input type="password" name="password" id="pass" placeholder="Enter Password" required>
                <span id="toggle" style="position:absolute; right:15px; top:45px; cursor:pointer; color:var(--primary); font-size:0.7rem; font-weight:bold;">SHOW</span>
            </div>
            
            <button type="submit" name="login" class="btn" id="loginBtn">
                <span id="btnText">Enter System</span>
                <center><div id="loader" class="spinner" style="display: none;"></div></center>
            </button>

            <p style="text-align:center; margin-top:20px; font-size:0.9rem;">
                New user? <a href="register.php" style="color:var(--primary); text-decoration:none;">Register here</a>
            </p>
        </form>
    </div>
   
   

    <script>
        // Password Visibility Toggle
        const passField = document.getElementById('pass');
        const toggleBtn = document.getElementById('toggle');

        toggleBtn.addEventListener('click', function() {
            const type = passField.type === 'password' ? 'text' : 'password';
            passField.type = type;
            this.textContent = type === 'password' ? 'SHOW' : 'HIDE';
        });

        // Cinematic Loading Logic
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const btnText = document.getElementById('btnText');
        const loader = document.getElementById('loader');

        loginForm.onsubmit = function() {
            loginBtn.style.pointerEvents = "none";
            btnText.style.opacity = "0";
            setTimeout(() => {
                btnText.style.display = "none";
                loader.style.display = "block";
            }, 200);
        };
    </script>
</body>
</html>