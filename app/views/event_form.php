<?php
$title = $id === null ? 'Add event' : 'Edit event';
$action = $id === null ? 'events/create' : 'events/edit';
require __DIR__ . '/layout_start.php';
?>

<div class="row">
    <section class="col-lg-8">
        <a href="index.php?url=events">Back to events</a>
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
                <input type="hidden" name="event_id" value="<?= $id ?>">
            <?php endif; ?>

            <div class="card-body p-4 row g-3 m-0">

                <?php foreach ($fields as $field => [$label, $limit]): ?>
                    <div class="col-sm-6">
                        <label class="form-label" for="<?= $escape($field) ?>"><?= $escape($label) ?></label>
                        <input class="form-control" id="<?= $escape($field) ?>" name="<?= $escape($field) ?>"
                            type="text" maxlength="<?= $limit ?>" required
                            value="<?= $escape($data[$field] ?? '') ?>">
                    </div>
                <?php endforeach; ?>

                <div class="col-sm-6">
                    <label class="form-label" for="event_date">Event date</label>
                    <input class="form-control" id="event_date" name="event_date"
                        type="date" required value="<?= $escape($data['event_date'] ?? '') ?>">
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="max_players">Maximum players</label>
                    <input class="form-control" id="max_players" name="max_players"
                        type="number" min="1" max="2147483647" step="1" required value="<?= $escape($data['max_players'] ?? '') ?>">
                </div>
            </div>
            <div class="card-footer bg-body p-3">
                <button class="btn btn-primary" type="submit">Save event</button>
            </div>
        </form>
    </section>
</div>

<?php require __DIR__ . '/layout_end.php'; ?>
