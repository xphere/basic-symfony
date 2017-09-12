<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../lib/autoload.php';

if (!isset($_SERVER['APP_ENV'])) {
    (new Dotenv())->load(__DIR__.'/../.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
    Debug::enable();
}

$kernel = new Kernel($_SERVER['APP_ENV'], $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
