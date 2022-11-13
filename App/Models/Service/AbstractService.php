<?php

namespace App\Models\Service;

use App\Models\Resource\AbstractResource;
use Laminas\Di\Di;

abstract class AbstractService
{
    protected $di;
    protected $resource;

    public function __construct(Di $di, AbstractResource $resource = null)
    {
        $this->di = $di;
        $this->resource = $resource;
    }
}
