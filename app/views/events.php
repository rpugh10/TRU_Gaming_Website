<?php
$title = 'Events';
require __DIR__ . '/layout_start.php';
?>

<header class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <a href="index.php?url=dashboard">Dashboard</a>
        <h1 class="h2 mt-2 mb-0">Events</h1>
    </div>
    <?php if ($canManage): ?>
        <a class="btn btn-primary" href="index.php?url=events/create">Add event</a>
    <?php endif; ?>
</header>

<?php if ($error !== null): ?>
    <p class="alert alert-danger" role="alert"><?= $escape($error) ?></p>
<?php endif; ?>

<?php if ($events === []): ?>
    <p class="card card-body text-body-secondary">No events yet.</p>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="table-responsive" tabindex="0" role="region" aria-label="Events table">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Event</th>
                        <th scope="col">Game</th>
                        <th scope="col">Date</th>
                        <th scope="col">Location</th>
                        <th scope="col">Player limit</th>
                        <?php if ($canManage): ?>
                            <th scope="col">Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <?php $id = (int) $event['event_id']; ?>
                        <tr>
                            <td><?= $escape($event['event_name']) ?></td>
                            <td><?= $escape($event['game_title']) ?></td>
                            <td><?= $escape($event['event_date']) ?></td>
                            <td><?= $escape($event['location']) ?></td>
                            <td><?= (int) $event['max_players'] ?></td>
                            <?php if ($canManage): ?>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a class="btn btn-sm btn-outline-primary"
                                            href="index.php?url=events/edit&amp;id=<?= $id ?>">Edit</a>
                                        <form method="post" action="index.php?url=events/delete">
                                            <input type="hidden" name="event_id" value="<?= $id ?>">
                                            <input type="hidden" name="csrf_token" value="<?= $escape($token) ?>">
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/layout_end.php'; ?>
