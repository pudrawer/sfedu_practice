<?php

namespace App\Router;

use App\Controllers\ControllerInterface;
use App\Controllers\HomepageController;
use App\Controllers\NotFoundController;

class Router
{
    public function chooseController(string $webPath): ?ControllerInterface
    {
        $page = explode('/', $webPath)[1];

        if ($page == "") {
            return new HomepageController();
        }

        $page = ucfirst($page) . 'Controller';
        $controller = 'App\Controllers\\' . $page;

        if (class_exists($controller)) {
            return new $controller();
        } else {
            return new NotFoundController();
        }
    }
}
