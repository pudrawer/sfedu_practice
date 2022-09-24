<?php

namespace App\Router;

use App\Controllers\ControllerInterface;
use App\Controllers\HomepageController;
use App\Controllers\NotFoundController;
use App\Models\Session\Session;

class Router
{
    public function chooseController(string $webPath): ?ControllerInterface
    {
        Session::getInstance()->start();

        if ($queryPos = strpos($webPath, '?')) {
            $webPath = substr($webPath, 0, $queryPos);
        }
        $webPath = trim($webPath, '/');

        $getParam = $_GET;

        if ($webPath == "") {
            return new HomepageController($getParam);
        }

        $webPath = ucfirst($webPath) . 'Controller';
        $controller = 'App\Controllers\\' . $webPath;

        if (class_exists($controller)) {
            return new $controller($getParam);
        } else {
            return new NotFoundController($getParam);
        }
    }
}
