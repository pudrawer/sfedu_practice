#!/usr/bin/php
<?php

require 'vendor/autoload.php';

const ENV_CACHE_KEY = 'env';
define('APP_ROOT', dirname(__FILE__));

$cachedEnv = \App\Models\Cache\CacheFactory::getInstance();
if ($cachedEnv) {
    $cachedEnv->del(ENV_CACHE_KEY);

    if ($cachedEnv->get(ENV_CACHE_KEY)) {
        echo 'Cached env data was not deleted' . PHP_EOL;
    } else {
        echo 'Cached env data was deleted' . PHP_EOL;
    }
} else {
    echo 'Env not cached' . PHP_EOL;
}
