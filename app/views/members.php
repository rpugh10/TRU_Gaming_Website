<?php $escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Members</title>
</head>
<body>
    <h1>Members</h1>
    <p><a href="index.php?url=dashboard">Dashboard</a> | <a href="index.php?url=members/create">Add member</a></p>
    <?php if ($error !== null): ?>
        <p role="alert"><?= $escape($error) ?></p>
    <?php endif; ?>
    <?php if ($members === []): ?>
        <p>No members yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr><th>Name</th><th>Gamertag</th><th>Email</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member): ?>
                    <?php $id = (int) $member['member_id']; ?>
                    <tr>
                        <td><?= $escape($member['real_name']) ?></td>
                        <td><?= $escape($member['gamertag']) ?></td>
                        <td><?= $escape($member['email']) ?></td>
                        <td>
                            <a href="index.php?url=members/edit&amp;id=<?= $id ?>">Edit</a>
                            <form method="post" action="index.php?url=members/delete">
                                <input type="hidden" name="member_id" value="<?= $id ?>">
                                <input type="hidden" name="csrf_token" value="<?= $escape($token) ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
