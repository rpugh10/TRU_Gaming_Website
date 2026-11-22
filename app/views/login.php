<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>TRU Gaming Club Login</h2>

<?php if ($error !== null): ?>
    <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<form method="POST"
      action="index.php?url=login">

    <p>
        Username
    </p>

    <input
        type="text"
        name="username"
        required>

    <br><br>

    <p>
        Password
    </p>

    <input
        type="password"
        name="password"
        required>

    <br><br>

    <button type="submit">
        Login
    </button>

</form>

</body>
</html>
