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
                    <fieldset class="col-12">
                        <legend class="h5">Member login</legend>
                        <p class="form-text">Create a login below, or enter an existing user ID to link an account.</p>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label" for="username">Username</label>
                                <input class="form-control" id="username" name="username" type="text"
                                    maxlength="50" autocomplete="off" value="<?= $escape($data['username'] ?? '') ?>">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="password">Password (at least 12 characters)</label>
                                <input class="form-control" id="password" name="password" type="password"
                                    autocomplete="new-password">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="password_confirmation">Confirm password</label>
                                <input class="form-control" id="password_confirmation" name="password_confirmation"
                                    type="password" autocomplete="new-password">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="user_id">Existing user ID</label>
                                <input class="form-control" id="user_id" name="user_id" type="number"
                                    min="1" value="<?= $escape($data['user_id'] ?? '') ?>">
                            </div>
                        </div>
                    </fieldset>
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
