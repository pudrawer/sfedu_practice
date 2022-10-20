<?php

use App\PageHandler;

define('APP_ROOT', __DIR__ . '/..');

require_once APP_ROOT . '/vendor/autoload.php';

PageHandler::getInstance()->handlePage();
