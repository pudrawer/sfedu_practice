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

    public function getPostParam(string $key)
    {
        return $_POST["$key"] ?? null;
    }

    public function isGetMethod(): bool
    {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }
}
