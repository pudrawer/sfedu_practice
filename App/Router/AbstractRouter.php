<?php

namespace App\Router;

use App\Controllers\ControllerInterface;
use App\Controllers\Web\NotFoundController;
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
