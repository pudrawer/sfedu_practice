<?php

namespace App\Models\Cache;

use Predis\Client;

class PredisCache extends AbstractCache
{
    protected $predis;

    public function __construct(Client $predis)
    {
        $this->predis = $predis;
    }

    public function get(string $key): ?string
    {
        return $this->predis->get($key);
    }

    public function set(string $key, string $value): self
    {
        $this->predis->set($key, $value);

        return $this;
    }

    public function del(string $key): self
    {
        $this->predis->del($key);

        return $this;
    }
}
