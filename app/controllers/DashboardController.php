<?php

class DashboardController
{
    public function index()
    {
        if (!isset($_SESSION['user_id']))
        {
            header("Location:index.php?url=login");
            exit;
        }

        $username = htmlspecialchars($_SESSION['username'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $role = htmlspecialchars($_SESSION['role'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
        $token = htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        echo "<h1>Dashboard " . $username . "</h1>";

        echo "<p>Welcome "
            . $username
            . "</p>";

        echo "<p>Role: "
            . $role
            . "</p>";

        echo "<form method='post' action='index.php?url=logout'>
                <input type='hidden' name='csrf_token' value='$token'>
                <button type='submit'>Logout</button>
              </form>";
    }
}
