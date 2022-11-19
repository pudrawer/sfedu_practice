<?php

namespace App\Core\Controllers\Console;

use App\Core\Controllers\ControllerInterface;

class EnvCleanController implements ControllerInterface
{
    protected const ENV_CACHE_KEY = 'env';

    public function execute(): bool
    {
        $cachedEnv = \App\Models\Cache\CacheFactory::getInstance();

        if (!$cachedEnv) {
            return false;
        }

        $cachedEnv->del(self::ENV_CACHE_KEY);

        return !$cachedEnv->get(self::ENV_CACHE_KEY);
    }
}
