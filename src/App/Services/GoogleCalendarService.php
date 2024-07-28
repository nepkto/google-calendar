<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;

class GoogleCalendarService
{
    private $client;
    private $service;

    public function __construct()
    {
        $config = require __DIR__ . '/../../Config/config.php';

        $this->client = new Google_Client();
        $this->client->setAuthConfig($config['google_calendar']['client_secret_path']);
        $this->client->addScope(Google_Service_Calendar::CALENDAR);
        $this->client->setRedirectUri($config['google_calendar']['redirect_uri']);
        $this->client->setAccessType('offline');

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->setAccessToken($_SESSION['access_token']);
        }
      
        if ($this->isAccessTokenExpired()) {
            if (isset($_SESSION['refresh_token'])) {
                $_SESSION['access_token'] = $this->refreshAccessToken();
            }
        }

        $this->service = new Google_Service_Calendar($this->client);
       
    }

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function setAccessToken($token)
    {
        $this->client->setAccessToken($token);
    }

    public function fetchAccessTokenWithAuthCode($code)
    {
        return $this->client->fetchAccessTokenWithAuthCode($code);
    }

    public function isAccessTokenExpired()
    {
        return $this->client->isAccessTokenExpired();
    }

    public function refreshAccessToken()
    {
        $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken($_SESSION['refresh_token']));
        return $this->client->getAccessToken();
    }

    public function listEvents()
    {
        $calendarId = 'primary';
        $optParams = [
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        ];
        return $this->service->events->listEvents($calendarId, $optParams);
    }

    public function createEvent($summary, $description, $startDateTime, $endDateTime)
    {
        $event = new \Google_Service_Calendar_Event([
            'summary' => $summary,
            'description' => $description,
            'start' => ['dateTime' => $startDateTime, 'timeZone' => 'Asia/Kathmandu'],
            'end' => ['dateTime' => $endDateTime, 'timeZone' => 'Asia/Kathmandu'],
        ]);

        $calendarId = 'primary';
        return $this->service->events->insert($calendarId, $event);
    }

    public function deleteEvent($eventId)
    {
        $calendarId = 'primary';
        $this->service->events->delete($calendarId, $eventId);
    }
}