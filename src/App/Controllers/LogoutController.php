<?php

namespace App\Controllers;

class LogoutController
{
    public function logout()
    {
        session_destroy();
        session_unset();
        header("Location: /");
        exit();
    }
}
