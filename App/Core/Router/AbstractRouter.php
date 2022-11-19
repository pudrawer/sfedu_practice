<?php

namespace App\Core\Router;

use App\Core\Controllers\ControllerInterface;
use Laminas\Di\Di;

abstract class AbstractRouter
{
    protected $di;

    public function __construct(Di $di)
    {
        $this->di = $di;
    }

    abstract public function chooseController(string $path): ?ControllerInterface;
}
