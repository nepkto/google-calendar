<?php
namespace App\Controllers;

use App\Services\GoogleCalendarService;

class HomeController
{
    private $calendarService;

    public function __construct()
    {

        if (!empty($_SESSION['access_token'])) {
            header("Location: " . baseUrl(). "event");
            exit();
        }
        $this->calendarService = new GoogleCalendarService();
    }
    public function index()
    {
        
        $authUrl = $this->calendarService->getAuthUrl();
        require __DIR__ . '/../../../Views/home.php';
        exit();
    }
}
