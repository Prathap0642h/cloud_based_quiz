<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Register | Dream AI</title>
</head>
<body>
    <div class="card">
        <h1 class="cinematic-text" style="text-align: center; margin-bottom: 15px;">Create Account</h1>

        <?php 
        // Handles errors passed from register_process.php
        if (isset($_GET['error'])) {
            if ($_GET['error'] == 'taken') {
                echo '<div class="alert-error">This username is already taken!</div>';
            } elseif ($_GET['error'] == 'server') {
                echo '<div class="alert-error">Server error. Please try again.</div>';
            }
        }
        ?>

        <form action="register_process.php" method="POST" id="regForm" autocomplete="off">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Choose a username" required autocomplete="off">
            </div>
            
            <div class="input-group" style="position: relative; margin-bottom: 25px;">
                <label>Password</label>
                <input type="password" name="password" id="regPass" placeholder="Create a password" required autocomplete="new-password">
                <span id="toggleReg" style="position:absolute; right:15px; top:45px; cursor:pointer; color:var(--primary); font-size:0.7rem; font-weight:bold;">SHOW</span>
            </div>
            
            <button type="submit" name="register" class="btn" id="regBtn">
                <span id="regBtnText">Register Now</span>
                <div id="regLoader" class="spinner" style="display: none;"></div>
            </button>

            <p style="text-align:center; margin-top:20px; font-size:0.9rem;">
                Already have an account? <a href="login.php" style="color:var(--primary); text-decoration:none;">Login here</a>
            </p>
        </form>
    </div>

    <script>
        // Password Visibility Toggle
        const regPass = document.getElementById('regPass');
        document.getElementById('toggleReg').addEventListener('click', function() {
            const type = regPass.type === 'password' ? 'text' : 'password';
            regPass.type = type;
            this.textContent = type === 'password' ? 'SHOW' : 'HIDE';
        });

        // Loading Animation for Button
        const regForm = document.getElementById('regForm');
        const regBtn = document.getElementById('regBtn');
        const regBtnText = document.getElementById('regBtnText');
        const regLoader = document.getElementById('regLoader');

        regForm.onsubmit = function() {
            regBtn.style.pointerEvents = "none";
            regBtnText.style.display = "none";
            regLoader.style.display = "block";
        };
    </script>
</body>
</html>