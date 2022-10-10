<?php

namespace App\Router;

use App\Controllers\ControllerInterface;
use App\Controllers\NotFoundWebController;

abstract class AbstractRouter
{
    /**
     * @return NotFoundWebController|ControllerInterface
     */
    public static function chooseRouter(string $path)
    {
        $routerList = [
            new WebRouter(),
            new ApiRouter(),
        ];

        foreach ($routerList as $router) {
            $controller = $router->chooseController($path);

            if ($controller) {
                return $controller;
            }
        }

        return new NotFoundWebController();
    }

    abstract public function chooseController(string $path): ?ControllerInterface;
}
