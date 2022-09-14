<?php

use App\PageHandler;

require_once './vendor/autoload.php';

define('APP_ROOT', dirname(__FILE__));

PageHandler::getInstance()->handlePage();
