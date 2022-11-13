<?php

namespace App\Router;

use App\Controllers\ControllerInterface;
use App\Controllers\Web\HomepageController;
use App\Controllers\Web\WrongController;
use App\Models\Logger;
use App\Models\Session\Session;
use Laminas\Di\Di;

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
            return $this->di->get(
                HomepageController::class,
                ['di' => $this->di]
            );
        }

        $path = ucfirst($path) . 'Controller';
        $controller = 'App\Controllers\Web\\' . $path;

        if (class_exists($controller)) {
            /** @var Session $session */
            $session = $this->di->get(Session::class);
            $session->start();

            set_error_handler(function (
                int $errno,
                string $errstr,
                string $errfile
            ) {
                $controller = $this->di->get(
                    WrongController::class,
                    ['di' => $this->di]
                );
                $controller->execute();

                $logger = $this->di->get(Logger::class);
                $logger->putError(implode(PHP_EOL, [
                    $errno,
                    $errstr,
                    $errfile,
                ]));

                return true;
            }, E_ALL);

            return $this->di->get(
                $controller,
                [
                    'params' => $getParam,
                    'di'     => $this->di,
                ]
            );
        }

        return null;
    }
}
