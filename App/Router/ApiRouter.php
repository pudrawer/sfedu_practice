<?php

namespace App\Router;

use App\Controllers\ControllerInterface;
use App\Controllers\HomepageWebController;
use App\Controllers\WrongWebController;
use App\Models\Session\Session;

class ApiRouter extends AbstractRouter
{
    private const CONTROLLER_NAME = 0;

    public function chooseController(string $path): ?ControllerInterface
    {
        $path = trim($path, '/');

        $paramPos = strpos($path, '/');
        $paramList = [];
        if ($paramPos) {
            $paramList = explode('/', substr($path, $paramPos + 1));
        }

        $controller = ucfirst(explode('/', $path)[self::CONTROLLER_NAME]);
        $controller = "App\Api\Controllers\\{$controller}ApiController";
        if (class_exists($controller)) {
            set_error_handler(function (
                int $errno,
                string $errstr,
                string $errfile
            ) {
                $controller = new WrongWebController();
                $controller->execute();

                return true;
            }, E_ALL);

            return new $controller($paramList ?? []);
        }

        return null;
    }
}
