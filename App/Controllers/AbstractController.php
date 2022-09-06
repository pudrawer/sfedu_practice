<?php

namespace App\Controllers;

abstract class AbstractController implements ControllerInterface
{
    protected $getParam = [];

    public function __construct(?array $getParam)
    {
        $this->getParam = $getParam;
    }
}