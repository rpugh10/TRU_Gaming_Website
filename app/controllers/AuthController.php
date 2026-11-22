<?php

class AuthController
{
    public function showLogin()
    {
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        require __DIR__ . '/../views/login.php';
    }

    public function authenticate()
    {
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        $userModel = new User();

        $user = is_string($username) && is_string($password)
            ? $userModel->findByUsername(trim($username))
            : false;

        if(
            $user &&
            password_verify(
                $password,
                $user['password_hash']
            )
        )
        {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: index.php?url=dashboard");
            exit;
        }

        $_SESSION['error'] = "Invalid Username or Password";
        header("Location: index.php?url=login");
        exit;
    }

    public function logout()
    {
        session_unset();

        session_destroy();

        header("Location:index.php?url=login");
        exit;
    }
}
