<?php

use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../autoload.php';

$kernel = new Kernel('prod', false);
/*
$kernel = new class($kernel) extends Symfony\Component\HttpKernel\HttpCache\HttpCache {};
Request::enableHttpMethodParameterOverride();
*/
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
