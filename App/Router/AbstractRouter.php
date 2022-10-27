<?php

namespace App\Router;

use App\Controllers\Api\WrongApiController;
use App\Controllers\ControllerInterface;
use App\Controllers\Web\NotFoundController;
use App\Exception\Exception;
use App\Models\Logger\Logger;

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
