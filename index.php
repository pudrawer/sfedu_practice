<?php

use App\Router\Router;

require_once './vendor/autoload.php';

define('APP_ROOT', dirname(__FILE__));
define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD'] ?? 'GET');

$router = new Router();
$controller = $router->chooseController($_SERVER['REQUEST_URI'] ?? '');
$controller->execute();
