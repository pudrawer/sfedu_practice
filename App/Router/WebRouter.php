<?php

namespace App\Router;

use App\Controllers\ControllerInterface;
use App\Controllers\Web\HomepageController;
use App\Controllers\Web\WrongController;
use App\Models\Session\Session;
use App\Models\Logger\Logger;

class WebRouter extends AbstractRouter
{
    public function chooseController(string $path): ?ControllerInterface
    {
        if ($queryPos = strpos($path, '?')) {
            $path = substr($path, 0, $queryPos);
        }
        $path = trim($path, '/');

        $getParam = $_GET;

        if ($path == '') {
            return new HomepageController($getParam);
        }

        $path = ucfirst($path) . 'Controller';
        $controller = 'App\Controllers\Web\\' . $path;

        if (class_exists($controller)) {
            Session::getInstance()->start();

            set_error_handler(function (
                int $errno,
                string $errstr,
                string $errfile
            ) {
                $controller = new WrongController();
                $controller->execute();

                Logger::getInstance()->putError(implode(PHP_EOL, [
                    $errno,
                    $errstr,
                    $errfile,
                ]));

                return true;
            }, E_ALL);

            return new $controller($getParam);
        }

        return null;
    }
}
