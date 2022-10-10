<?php

namespace App\Router;

use App\Controllers\ControllerInterface;
use App\Controllers\HomepageWebController;
use App\Controllers\WrongWebController;
use App\Models\Session\Session;

class ApiRouter extends AbstractRouter
{
    private const CONTROLLER_NAME = 0;
    private const PARAM = 1;

    public function chooseController(string $path): ?ControllerInterface
    {
        $path = trim($path, '/');
        $paramList = explode('/', $path);

        $controller = ucfirst($paramList[self::CONTROLLER_NAME]);
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

            return new $controller($paramList[self::PARAM] ?? null);
        }

        return null;
    }
}
