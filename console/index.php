#!/usr/bin/php
<?php

define('APP_ROOT', __DIR__ . '/..');

require APP_ROOT . '/vendor/autoload.php';

$router = new \App\Router\ConsoleRouter();
$classList = $router->chooseController(explode(':', $argv[1]) ?? '');

$controller = $classList['controller'];
$writer = $classList['writer'];

$result = $controller->execute();
$mode = 1;
foreach ($result as $value) {
    if (is_array($value)) {
        $mode = 2;
        break;
    }
}

$writer->write($result, $mode);
