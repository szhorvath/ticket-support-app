<?php
namespace App\Services\Auth;

class Auth
{
    public function user()
    {
        return $_SESSION['user'];
    }

    public function username()
    {
        if (!isset($_SESSION['user']['username'])) {
            return null;
        }
        return $_SESSION['user']['username'];
    }

    public function check()
    {
        return isset($_SESSION['user']);
    }

    public function isAdmin()
    {
        return isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin'];
    }

    public function logout()
    {
        unset($_SESSION['user']);
    }
}
