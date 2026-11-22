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

        echo "<h1>Dashboard</h1>";

        echo "<p>Welcome "
            . $_SESSION['username']
            . "</p>";

        echo "<p>Role: "
            . $_SESSION['role']
            . "</p>";

        echo "<a href='index.php?url=logout'>
                Logout
              </a>";
    }
}