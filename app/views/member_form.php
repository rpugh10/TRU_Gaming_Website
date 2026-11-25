<?php
$title = $id === null ? 'Add member' : 'Edit member';
$action = $id === null ? 'members/create' : 'members/edit';
require __DIR__ . '/layout_start.php';
?>

<div class="row">
    <section class="col-lg-8">
        <a href="index.php?url=members">Back to members</a>
        <h1 class="h2 mt-3 mb-4"><?= $escape($title) ?></h1>

        <?php if ($errors !== []): ?>
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= $escape($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="card shadow-sm" method="post" action="index.php?url=<?= $escape($action) ?>">
            <input type="hidden" name="csrf_token" value="<?= $escape($token) ?>">
            <?php if ($id !== null): ?>
                <input type="hidden" name="member_id" value="<?= $id ?>">
            <?php endif; ?>

            <div class="card-body p-4 row g-3 m-0">
                <?php if ($id === null): ?>
                    <div class="col-sm-6">
                        <label class="form-label" for="user_id">Existing user ID</label>
                        <input class="form-control" id="user_id" name="user_id"
                            type="number" min="1" required value="<?= $escape($data['user_id'] ?? '') ?>">
                    </div>
                <?php endif; ?>

                <?php foreach ($fields as $field => [$label, $limit]): ?>
                    <div class="col-sm-6">
                        <label class="form-label" for="<?= $escape($field) ?>"><?= $escape($label) ?></label>
                        <input class="form-control" id="<?= $escape($field) ?>" name="<?= $escape($field) ?>"
                            type="<?= $field === 'email' ? 'email' : 'text' ?>" maxlength="<?= $limit ?>" required
                            value="<?= $escape($data[$field] ?? '') ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="card-footer bg-body p-3">
                <button class="btn btn-primary" type="submit">Save member</button>
            </div>
        </form>
    </section>
</div>

<?php require __DIR__ . '/layout_end.php'; ?>
