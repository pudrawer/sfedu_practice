<?php

namespace App\Models\Selection;

use Laminas\Di\Di;

abstract class AbstractSelection
{
    protected $di;

    public function __construct(Di $di)
    {
        $this->di = $di;
    }

    abstract public function selectData(array $haystack): array;
}
