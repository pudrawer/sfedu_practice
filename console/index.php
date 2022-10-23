#!/usr/bin/php
<?php

define('APP_ROOT', __DIR__ . '/..');

const ONE_DIMENSIONAL_ARRAY = 1;
const TWO_DIMENSIONAL_ARRAY = 2;

require APP_ROOT . '/vendor/autoload.php';

$router = new \App\Router\ConsoleRouter();

$modeParams = explode(':', $argv[1]);
$streamParams = $argv[2] ? explode(':', $argv[2]) : null;
$classList = $router->chooseController(
    is_array($modeParams) ? $modeParams : [],
    $streamParams
);

$controller = $classList['controller'];
$stream = $classList['stream'] ?? false;

$result = $controller->execute();

if ($stream) {
    $arrayMode = ONE_DIMENSIONAL_ARRAY;
    foreach ($result as $value) {
        if (is_array($value)) {
            $arrayMode = TWO_DIMENSIONAL_ARRAY;
            break;
        }
    }

    $stream->write($result, $arrayMode);
}
