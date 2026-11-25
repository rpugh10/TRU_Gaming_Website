<?php
$title = 'Dashboard';
require __DIR__ . '/layout_start.php';
?>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <h1 class="h2">Dashboard</h1>
        <p class="mb-1">Welcome <?= $escape($username) ?></p>
        <p class="text-body-secondary mb-4">Role: <?= $escape($role) ?></p>

        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-primary" href="index.php?url=events">Events</a>
            <?php if ($role === 'admin'): ?>
                <a class="btn btn-outline-primary" href="index.php?url=members">Manage members</a>
            <?php endif; ?>
            <form method="post" action="index.php?url=logout">
                <input type="hidden" name="csrf_token" value="<?= $escape($token) ?>">
                <button class="btn btn-outline-secondary" type="submit">Logout</button>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/layout_end.php'; ?>
