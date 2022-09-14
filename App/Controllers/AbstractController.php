<?php

namespace App\Controllers;

abstract class AbstractController implements ControllerInterface
{
    protected $getParams = [];

    public function __construct(array $params = [])
    {
        $this->getParams = $params;
    }

    public function redirectTo(string $webPath = '')
    {
        header("Location: http://localhost:8080/$webPath");
        exit;
    }
}
