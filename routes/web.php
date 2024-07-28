<?php

$router->addRoute('GET', '/', 'App\Controllers\HomeController', 'index');

$router->addRoute('GET', '/event', 'App\Controllers\CalendarController', 'index');
$router->addRoute('GET', '/event/create', 'App\Controllers\CalendarController', 'create');
$router->addRoute('POST', '/event', 'App\Controllers\CalendarController', 'store');
$router->addRoute('DELETE', '/event/{id}', 'App\Controllers\CalendarController', 'delete');

$router->addRoute('GET', '/events-list', 'App\Controllers\CalendarController', 'eventLists');
$router->addRoute('POST', '/disconnect', 'App\Controllers\LogoutController', 'logout');

$router->addCallbackRoute('/callback', 'App\Controllers\CallbackController', 'handleCallback');

$router->dispatch();