<?php

class Auth
{
    public static function check()
    {
        return isset($_SESSION['user_id']);
    }

    public static function user()
    {
        return $_SESSION;
    }

    public static function role()
    {
        return $_SESSION['role'] ?? null;
    }
}