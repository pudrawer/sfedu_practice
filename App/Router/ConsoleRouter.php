<?php

namespace App\Router;

use App\Controllers\ControllerInterface;
use App\Exception\Exception;
use App\Models\Service\Writer\AbstractWriter;

class ConsoleRouter
{
    protected const NEEDED_CONTROLLER = 0;
    protected const WRITER_CONTROLLER = 1;

    public function chooseController(array $controllers): array
    {
        $controller = ucfirst($controllers[self::NEEDED_CONTROLLER]);
        $controller = "App\Controllers\Console\\{$controller}Controller";
        if (!class_exists($controller)) {
            throw new Exception('Bad param' . PHP_EOL);
        }

        $controller = new $controller();

        $writer = ucfirst($controllers[self::WRITER_CONTROLLER]);
        $writer = "App\Models\Service\Writer\\{$writer}Writer";
        if (!class_exists($writer)) {
            throw new Exception('Bad param' . PHP_EOL);
        }

        $writer = new $writer($controller);

        return [
            'controller' => $controller,
            'writer'     => $writer,
        ];
    }
}
