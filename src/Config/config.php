<?php

return [
    'google_calendar' => [
        'client_secret_path' => __DIR__ . '/../../client_secret.json',
        'redirect_uri' => baseUrl() . 'callback.php',
    ],
];
