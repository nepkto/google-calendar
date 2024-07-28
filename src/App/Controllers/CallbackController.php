<?php

namespace App\Controllers;

use App\Services\GoogleCalendarService;

class CallbackController
{
    private $calendarService;

    public function __construct()
    {
        $this->calendarService = new GoogleCalendarService();
    }
    public function handleCallback()
    {

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->calendarService->setAccessToken($_SESSION['access_token']);
        } elseif (isset($_GET['code'])) {
            $token = $this->calendarService->fetchAccessTokenWithAuthCode($_GET['code']);
            $_SESSION['access_token'] = $token;

            if (isset($token['refresh_token'])) {
                $_SESSION['refresh_token'] = $token['refresh_token'];
            }
        }

        $this->calendarService->setAccessToken($_SESSION['access_token']);

        if ($this->calendarService->isAccessTokenExpired()) {
            $_SESSION['access_token'] = $this->calendarService->refreshAccessToken();
        }
        $googleEvents = $this->calendarService->listEvents();

        header("Location: " . baseUrl() . "event");
        exit();
    }
}
