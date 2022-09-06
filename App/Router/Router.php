<?php

namespace App\Router;

use App\Controllers\ControllerInterface;
use App\Controllers\HomepageController;
use App\Controllers\NotFoundController;

class Router
{
    const CONTROLLER_NAME = 0;
    const GET_PARAM = 1;

    public function chooseController(string $webPath): ?ControllerInterface
    {

        $page = explode('/', $webPath)[self::GET_PARAM];
        $page = explode('?', $page)[self::CONTROLLER_NAME];

        $getParam = $_GET;

        if ($page == "") {
            return new HomepageController($getParam);
        }

        $page = ucfirst($page) . 'Controller';
        $controller = 'App\Controllers\\' . $page;

        if (class_exists($controller)) {
            return new $controller($getParam);
        } else {
            return new NotFoundController($getParam);
        }
    }
}
