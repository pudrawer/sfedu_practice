<?php

use App\Core\PageHandler;

define('APP_ROOT', __DIR__ . '/..');

require_once APP_ROOT . '/vendor/autoload.php';

$di = new \Laminas\Di\Di();
/** @var PageHandler $pageHandler */
$pageHandler = $di->get(PageHandler::class, ['di' => $di]);
$pageHandler->handlePage();
