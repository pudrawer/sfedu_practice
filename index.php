<?php

use App\Router\Router;

require_once './vendor/autoload.php';

$router = new Router();
$router->chooseController($_SERVER['REQUEST_URI'] ?? '');
