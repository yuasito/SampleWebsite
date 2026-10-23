<?php
require 'db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: index.php");
    exit;
}

// Process update request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name !== '' && $email !== '') {
        $stmt = $pdo->prepare(
            "UPDATE users SET name = ?, email = ? WHERE id = ?"
        );

        if ($stmt->execute([$name, $email, $id])) {
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

    <title>Update User | User Management</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <div class="dashboard-header">
        <div>
            <h2>✏️ Edit User</h2>
            <p class="subtitle">
                Update the user's information below.
            </p>
        </div>

        <button type="button" id="darkModeToggle">
            🌙 Dark Mode
        </button>
    </div>

    <div class="form-card">

        <form method="POST" action="update.php?id=<?= (int) $id ?>">

            <label for="name">Full Name</label>

            <input
                type="text"
                id="name"
                name="name"
                placeholder="Enter full name"
                value="<?= htmlspecialchars($user['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                required
            >

            <label for="email">Email Address</label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter email address"
                value="<?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                required
            >

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    ✓ Save Changes
                </button>

                <a href="index.php" class="btn-secondary">
                    Cancel
                </a>
            </div>

        </form>

    </div>

</div>

<script>
    const toggle = document.getElementById('darkModeToggle');

    function applyTheme(theme) {
        document.body.classList.toggle(
            'dark-mode',
            theme === 'dark'
        );

        toggle.textContent = theme === 'dark'
            ? '☀️ Light Mode'
            : '🌙 Dark Mode';
    }

    applyTheme(localStorage.getItem('theme') || 'light');

    toggle.addEventListener('click', function () {
        const isDark =
            document.body.classList.contains('dark-mode');

        const newTheme = isDark ? 'light' : 'dark';

        localStorage.setItem('theme', newTheme);
        applyTheme(newTheme);
    });
</script>

</body>
</html>
