<?php
require 'db.php';

// --- DELETE LOGIC ---
if (isset($_GET['delete_id'])) {
    $deleteId = filter_input(INPUT_GET, 'delete_id', FILTER_VALIDATE_INT);

    if ($deleteId !== false && $deleteId !== null && $deleteId > 0) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$deleteId]);
    }

    header("Location: index.php");
    exit;
}

// --- READ LOGIC ---
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="dashboard-header">
        <div>
            <h2>👥 User Management</h2>
            <p class="subtitle">Organize and manage system accounts efficiently.</p>
        </div>
        <button type="button" id="darkModeToggle">🌙 Dark Mode</button>
    </div>

    <div class="action-bar">
        <h3>All Users</h3>
        <a href="create.php" class="btn-primary">+ Add New User</a>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars((string) $user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                            <td class="actions">
                                <a href="update.php?id=<?= (int) $user['id'] ?>" class="btn-edit">Edit</a>
                                <a href="index.php?delete_id=<?= (int) $user['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="empty-state">
                            <strong>No users found</strong>
                            <p>Click "Add New User" to get started.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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