<?php

namespace App\Core\Router;

use App\Core\Controllers\ControllerInterface;
use App\Core\Controllers\Web\WrongController;
use App\Core\Models\Logger;
use App\Core\Models\Session\Session;
use App\ModuleSettingsAggregator;

class WebRouter extends AbstractRouter
{
    public function chooseController(string $path): ?ControllerInterface
    {
        if ($queryPos = strpos($path, '?')) {
            $path = substr($path, 0, $queryPos);
        }
        $path = $path == '/' ? $path : rtrim($path, '/');

        $getParam = $_GET;

        $controllerList = ModuleSettingsAggregator::getWebRoutes();
        $controller = $controllerList[$path] ?? null;

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
