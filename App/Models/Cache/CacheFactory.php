<?php

namespace App\Models\Cache;

use App\Models\Environment;
use Predis\Client;

class CacheFactory
{
    protected static $instance = null;

    protected function __construct()
    {
    }

    /**
     * @return Cache|Client|null
     */
    public static function getInstance()
    {
        if (self::$instance) {
            return self::$instance;
        }

        return self::$instance = self::getCache();
    }

    /**
     * @return Cache|Client|null
     */
    protected static function getCache()
    {
        $mode = Environment::getInstance()->getCacheMode();

        if ($mode == 'predis') {
            return new Client();
        } elseif ($mode == 'file') {
            return new Cache();
        }

        return null;
    }

    public static function checkEnvCache(): ?string
    {
        $predis    = new Client();
        $fileCache = new Cache();
        $predisData = $predis->get('env');
        $fileData   = $fileCache->get('env');

        return $predisData ?? $fileData;
    }
}
