<?php

namespace App\Controllers\Console;

use App\Controllers\ControllerInterface;

class EnvCleanController implements ControllerInterface
{
    protected const ENV_CACHE_KEY = 'env';

    public function execute(): bool
    {
        $cachedEnv = \App\Models\Cache\CacheFactory::getInstance();

        if ($cachedEnv) {
            $cachedEnv->del(self::ENV_CACHE_KEY);

            if ($cachedEnv->get(self::ENV_CACHE_KEY)) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}
