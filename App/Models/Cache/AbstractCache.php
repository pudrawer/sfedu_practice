<?php

namespace App\Models\Cache;

abstract class AbstractCache
{
    abstract public function get(string $key): ?string;
    abstract public function set(string $key, string $value);
    abstract public function del(string $key);
}
