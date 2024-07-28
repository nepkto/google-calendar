<?php

use App\Core\Router;


ob_start();

require_once __DIR__ . '/../bootstrap/app.php';

$router = new Router();

require_once __DIR__ . '/../routes/web.php';

ob_clean();
$router->dispatch();

