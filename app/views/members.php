<?php
$title = 'Members';
require __DIR__ . '/layout_start.php';
?>

<header class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <a href="index.php?url=dashboard">Dashboard</a>
        <h1 class="h2 mt-2 mb-0">Members</h1>
    </div>
    <a class="btn btn-primary" href="index.php?url=members/create">Add member</a>
</header>

<?php if ($error !== null): ?>
    <p class="alert alert-danger" role="alert"><?= $escape($error) ?></p>
<?php endif; ?>

<?php if ($members === []): ?>
    <p class="card card-body text-body-secondary">No members yet.</p>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="table-responsive" tabindex="0" role="region" aria-label="Members table">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Gamertag</th>
                        <th scope="col">Email</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                        <?php $id = (int) $member['member_id']; ?>
                        <tr>
                            <td><?= $escape($member['real_name']) ?></td>
                            <td><?= $escape($member['gamertag']) ?></td>
                            <td><?= $escape($member['email']) ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="index.php?url=members/edit&amp;id=<?= $id ?>">Edit</a>
                                    <form method="post" action="index.php?url=members/delete">
                                        <input type="hidden" name="member_id" value="<?= $id ?>">
                                        <input type="hidden" name="csrf_token" value="<?= $escape($token) ?>">
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/layout_end.php'; ?>
