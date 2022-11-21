<?php

namespace App\Core\Router;

use App\Core\Controllers\Api\WrongApiController;
use App\Core\Controllers\ControllerInterface;
use App\Core\Models\Logger;
use App\ModuleSettingsAggregator;

class ApiRouter extends AbstractRouter
{
    private const CONTROLLER_NAME = 0;

    public function chooseController(string $path): ?ControllerInterface
    {
        $path = trim($path, '/');

        $paramPos = strpos($path, '/');
        $paramList = [];
        if ($paramPos) {
            $paramList = explode('/', substr($path, $paramPos + 1));
        }

        $controllerList = ModuleSettingsAggregator::getApiRoutes();

        $controller = explode('/', $path)[self::CONTROLLER_NAME];

        $controller = $controllerList[$controller] ?? null;
        if (class_exists($controller)) {
            set_error_handler(function (
                int $errno,
                string $errstr,
                string $errfile
            ) {
                $controller = $this->di->get(
                    WrongApiController::class,
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
                    'di'    => $this->di,
                    'param' => $paramList ?? [],
                ]
            );
        }

        return null;
    }
}
