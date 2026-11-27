<?php

class AuthController
{
    public function showLogin()
    {
        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['signup_success'] ?? null;
        unset($_SESSION['error'], $_SESSION['signup_success']);
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
        if(session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $token = $_POST['csrf_token'] ?? '';
        $sessionToken = $_SESSION['csrf_token'] ?? '';

        if (!is_string($token) || $sessionToken === '' || !hash_equals($sessionToken, $token)) {
            http_response_code(403);
            echo "Invalid CSRF token";
            exit;
        }

        $_SESSION = [];

        session_destroy();

        header("Location:index.php?url=login");
        exit;
    }
}
