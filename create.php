<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!empty($name) && !empty($email)) {
        $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        if ($stmt->execute([$name, $email])) {
            header("Location: index.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="dashboard-header">
        <div>
            <h2>➕ Add New User</h2>
            <p class="subtitle">Enter details to create a new user account.</p>
        </div>
        <button type="button" id="darkModeToggle">🌙 Dark Mode</button>
    </div>

    <div class="form-card">
        <form method="POST" action="create.php">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="Enter Full Name" required>

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter Email Address" required>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Submit</button>
                <a href="index.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    const toggle = document.getElementById('darkModeToggle');

    function applyTheme(theme) {
        document.body.classList.toggle('dark-mode', theme === 'dark');
        toggle.textContent = theme === 'dark' ? '☀️ Light Mode' : '🌙 Dark Mode';
    }

    const savedTheme = localStorage.getItem('theme') || 'light';
    applyTheme(savedTheme);

    toggle.addEventListener('click', function () {
        const isDark = document.body.classList.contains('dark-mode');
        const newTheme = isDark ? 'light' : 'dark';
        localStorage.setItem('theme', newTheme);
        applyTheme(newTheme);
    });
</script>

</body>
</html>