<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Delete Logic
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM questions WHERE id = $id");
    header("Location: manage_questions.php?msg=Question Deleted");
    exit();
}

$questions = $conn->query("SELECT * FROM questions ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Manage Questions | Dream AI</title>
</head>
<body>
    <div class="sidebar">
        <h2 class="cinematic-text"><i class="fa-solid fa-wand-magic-sparkles"></i> Admin</h2>
        <div class="sidebar-menu">
            <a href="admin.php" class="side-btn"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="add_questions.php" class="side-btn"><i class="fa-solid fa-plus"></i> Add Question</a>
            <a href="manage_questions.php" class="side-btn active"><i class="fa-solid fa-list-check"></i> Manage Qs</a>
        </div>
    </div>

    <div class="main-content">
        <div class="dashboard-header">
            <h1 class="cinematic-text">Question Bank</h1>
            <p style="color: var(--text-muted);">Review and update your assessment content.</p>
        </div>

        <div class="card" style="max-width: 100%;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Question</th>
                        <th>Correct</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $questions->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td style="max-width: 400px;"><?php echo htmlspecialchars($row['question']); ?></td>
                        <td style="text-transform: uppercase; color: #10b981; font-weight: bold;"><?php echo $row['correct']; ?></td>
                        <td>
                            <a href="manage_questions.php?delete_id=<?php echo $row['id']; ?>" 
                               onclick="return confirm('Permanently delete this question?')" 
                               style="color: #ef4444; border: 1px solid #ef4444; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 0.8rem;">
                               <i class="fa-solid fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>