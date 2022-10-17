<?php

use App\PageHandler;

require_once './vendor/autoload.php';

define('APP_ROOT', dirname(__FILE__));

PageHandler::getInstance()->handlePage();
/*
$predis = new Predis\Client();
$predis->set('jopa', json_encode(['da']));
$value = $predis->get('jopa');
$value = json_decode($value);
echo $value;*/