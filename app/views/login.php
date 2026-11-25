<?php
$title = 'Login';
require __DIR__ . '/layout_start.php';
?>

<div class="row justify-content-center">
    <section class="col-sm-9 col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 mb-4">Login</h1>

                <?php if ($error !== null): ?>
                    <p class="alert alert-danger" role="alert"><?= $escape($error) ?></p>
                <?php endif; ?>

                <form method="post" action="index.php?url=login">
                    <div class="mb-3">
                        <label class="form-label" for="username">Username</label>
                        <input class="form-control" id="username" name="username"
                            type="text" autocomplete="username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input class="form-control" id="password" name="password"
                            type="password" autocomplete="current-password" required>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Login</button>
                </form>
            </div>
        </div>
    </section>
</div>

<?php require __DIR__ . '/layout_end.php'; ?>
