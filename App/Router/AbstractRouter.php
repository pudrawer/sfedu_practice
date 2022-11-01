<?php

namespace App\Router;

use App\Controllers\ControllerInterface;
use App\Controllers\Web\NotFoundController;

abstract class AbstractRouter
{
    abstract public function chooseController(string $path): ?ControllerInterface;

    /**
     * @return NotFoundController|ControllerInterface
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

        return new NotFoundController();
    }
}
