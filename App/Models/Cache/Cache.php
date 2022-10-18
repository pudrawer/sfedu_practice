<?php

namespace App\Models\Cache;

class Cache
{
    protected const CACHE_FILE = APP_ROOT . '/var/cache/';

    public function get(string $key): ?string
    {
        $isExists = file_exists(self::CACHE_FILE . "$key.json");

        $data = $isExists ? file_get_contents(
            self::CACHE_FILE . "$key.json"
        ) : null;
        return $data ?? null;
    }

    public function set(string $key, string $value): self
    {
        file_put_contents(
            self::CACHE_FILE . "$key.json",
            $value
        );

        return $this;
    }

    public function del(string $key): self
    {
        file_put_contents(
            self::CACHE_FILE . "$key.json",
            ''
        );

        return $this;
    }
}
