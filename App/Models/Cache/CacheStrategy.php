<?php

namespace App\Models\Cache;

use App\Exception\CacheException;
use App\Models\Cache\Cache;
use App\Models\Environment\Environment;
use Predis\Client;

class CacheStrategy
{
    /**
     * @return Cache|Client
     * @throws CacheException
     */
    public static function chooseCache()
    {
        $env = Environment::getInstance();

        if (($mode = $env->getCacheMode()) == 'predis') {
            return new Client();
        } elseif ($mode == 'file') {
            return new Cache();
        }

        throw new CacheException('Bad cache mode' . PHP_EOL);
    }
}
