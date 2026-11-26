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
<?php if ($success !== null): ?>
    <p class="alert alert-success" role="status"><?= $escape($success) ?></p>
<?php endif; ?>

<form id="event-search" class="mb-3" method="get" action="index.php">
    <input type="hidden" name="url" value="events">
    <label class="form-label fw-semibold" for="event-query">Search events</label>
    <div class="d-flex flex-wrap gap-2">
        <input class="form-control w-auto flex-grow-1" id="event-query" name="q" type="search"
            value="<?= $escape($query) ?>" maxlength="200" placeholder="Event name or game title"
            aria-controls="event-results">
        <button class="btn btn-outline-primary" type="submit">Search</button>
    </div>
</form>

<p id="event-search-status" class="text-body-secondary" role="status" aria-atomic="true"><?php
    if ($events === []) {
        echo $query === '' ? 'No events yet.' : 'No matching events.';
    } elseif ($query !== '') {
        echo count($events) . (count($events) === 1 ? ' event found.' : ' events found.');
    }
?></p>

<div id="event-results" class="card shadow-sm"<?= $events === [] ? ' hidden' : '' ?>>
    <div class="table-responsive" tabindex="0" role="region" aria-label="Events table">
        <table class="table table-striped align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col">Event</th>
                    <th scope="col">Game</th>
                    <th scope="col">Date</th>
                    <th scope="col">Location</th>
                    <th scope="col">Player limit</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php require __DIR__ . '/event_rows.php'; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="js/event-search.js" defer></script>
<?php require __DIR__ . '/layout_end.php'; ?>
