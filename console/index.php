#!/usr/bin/php
<?php

define('APP_ROOT', __DIR__ . '/..');

require APP_ROOT . '/vendor/autoload.php';

\App\Core\ConsoleHandler::getInstance()->handle($argv);
