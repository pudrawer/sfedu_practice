<?php

namespace App\Models\Cache;

use App\Exception\CacheException;
use App\Models\Environment\Environment;
use Predis\Client;

class CacheFactory
{
    protected static $instance = null;

    protected function __construct()
    {
    }

    /**
     * @return Cache|Client|null
     * @throws CacheException
     */
    public static function getInstance()
    {
        if (self::$instance) {
            return self::$instance;
        }

        return self::$instance = self::getCache();
    }

    /**
     * @return Cache|Client
     * @throws CacheException
     */
    protected static function getCache()
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
