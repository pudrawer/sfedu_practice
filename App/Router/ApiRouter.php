<?php

namespace App\Router;

use App\Controllers\Api\WrongApiController;
use App\Controllers\ControllerInterface;
use App\Models\Logger;

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

        $controller = ucfirst(explode('/', $path)[self::CONTROLLER_NAME]);
        $controller = "App\Controllers\Api\\{$controller}Controller";
        if (class_exists($controller)) {
            $da = 1;
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
