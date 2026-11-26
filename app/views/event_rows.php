<?php foreach ($events as $event): ?>
    <?php $id = (int) $event['event_id']; ?>
    <tr>
        <td><?= $escape($event['event_name']) ?></td>
        <td><?= $escape($event['game_title']) ?></td>
        <td><?= $escape($event['event_date']) ?></td>
        <td><?= $escape($event['location']) ?></td>
        <td><?= (int) $event['max_players'] ?></td>
        <td>
            <div class="d-flex flex-wrap gap-2">
                <form method="post" action="index.php?url=events/register">
                    <input type="hidden" name="event_id" value="<?= $id ?>">
                    <input type="hidden" name="csrf_token" value="<?= $escape($token) ?>">
                    <button class="btn btn-sm btn-primary" type="submit">Register</button>
                </form>
                <?php if ($canManage): ?>
                    <a class="btn btn-sm btn-outline-primary"
                        href="index.php?url=events/edit&amp;id=<?= $id ?>">Edit</a>
                    <form method="post" action="index.php?url=events/delete">
                        <input type="hidden" name="event_id" value="<?= $id ?>">
                        <input type="hidden" name="csrf_token" value="<?= $escape($token) ?>">
                        <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                    </form>
                <?php endif; ?>
            </div>
        </td>
    </tr>
<?php endforeach; ?>
