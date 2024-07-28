<?php

namespace App\Services;

class FlashMessage
{
    public static function set($message, $type = 'info', array $validationErrs = [])
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_message_type'] = $type;

        if (count($validationErrs) > 0) {
            $_SESSION['flash_validation_errors'] = $validationErrs;
        } else {
            unset($_SESSION['flash_validation_errors']);
        }
    }

    public static function display()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['flash_message'])) {
            $messageType = $_SESSION['flash_message_type'] ?? 'info';
            echo '<div class="' . 'alert alert-' . htmlspecialchars($messageType) . '">';
            echo htmlspecialchars($_SESSION['flash_message']);
            echo '</div>';
            self::unsetFlash();
        }
    }

    public static function displayValidation()
    {
        if (isset($_SESSION['flash_validation_errors'])) {
            echo '<div class="alert alert-danger">';
            echo '<ul>';
            foreach ($_SESSION['flash_validation_errors'] as $error) {
                echo '<li>' . $error . '</li>';
            }
            echo '</ul>';
            echo '</div>';
            unset($_SESSION['flash_validation_errors']);
        }
    }

    private static function unsetFlash()
    {
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_message_type']);
    }
}
