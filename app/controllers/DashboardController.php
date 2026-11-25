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

        $username = $_SESSION['username'];
        $role = $_SESSION['role'];
        $token = $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
        require __DIR__ . '/../views/dashboard.php';
    }
}
