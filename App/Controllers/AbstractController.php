<?php

namespace App\Controllers;

use Laminas\Di\Di;

abstract class AbstractController implements ControllerInterface
{
    protected $di;

    public function __construct(Di $di)
    {
        $this->di = $di;
    }
}
