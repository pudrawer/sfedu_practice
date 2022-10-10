<?php

namespace App\Router;

use App\Controllers\ControllerInterface;
use App\Controllers\HomepageWebController;
use App\Controllers\WrongWebController;
use App\Models\Session\Session;

class WebRouter extends AbstractRouter
{
    public function chooseController(string $path): ?ControllerInterface
    {
        if ($queryPos = strpos($path, '?')) {
            $path = substr($path, 0, $queryPos);
        }
        $path = trim($path, '/');

        $getParam = $_GET;

        if ($path == "") {
            return new HomepageWebController($getParam);
        }

        $path = ucfirst($path) . 'WebController';
        $controller = 'App\Controllers\\' . $path;

        if (class_exists($controller)) {
            Session::getInstance()->start();

            set_error_handler(function (
                int $errno,
                string $errstr,
                string $errfile
            ) {
                $controller = new WrongWebController();
                $controller->execute();

                return true;
            }, E_ALL);

            return new $controller($getParam);
        }

        return null;
    }
}
