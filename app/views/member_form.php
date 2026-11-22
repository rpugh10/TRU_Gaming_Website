<?php
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$title = $id === null ? 'Add member' : 'Edit member';
$action = $id === null ? 'members/create' : 'members/edit';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
</head>
<body>
    <h1><?= $title ?></h1>
    <p><a href="index.php?url=members">Back to members</a></p>
    <?php if ($errors !== []): ?>
        <ul role="alert">
            <?php foreach ($errors as $error): ?>
                <li><?= $escape($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="post" action="index.php?url=<?= $action ?>">
        <input type="hidden" name="csrf_token" value="<?= $escape($token) ?>">
        <?php if ($id === null): ?>
            <p><label>Existing user ID
                <input type="number" name="user_id" min="1" required value="<?= $escape($data['user_id'] ?? '') ?>">
            </label></p>
        <?php else: ?>
            <input type="hidden" name="member_id" value="<?= $id ?>">
        <?php endif; ?>
        <?php foreach ($fields as $field => [$label, $limit]): ?>
            <p><label><?= $escape($label) ?>
                <input type="<?= $field === 'email' ? 'email' : 'text' ?>" name="<?= $field ?>"
                    maxlength="<?= $limit ?>" required value="<?= $escape($data[$field] ?? '') ?>">
            </label></p>
        <?php endforeach; ?>
        <button type="submit">Save member</button>
    </form>
</body>
</html>
