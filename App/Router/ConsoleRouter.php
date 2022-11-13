<?php

namespace App\Router;

use App\Controllers\ControllerInterface;
use App\Exception\Exception;
use App\Models\Service\Writer\AbstractWriter;
use Laminas\Di\Di;

class ConsoleRouter
{
    protected const CONTROLLER  = 0;
    protected const FUNC_MODE   = 1;

    protected const FILE_NAME   = 0;
    protected const STREAM_MODE = 1;

    protected $di;

    public function __construct(Di $di)
    {
        $this->di = $di;
    }

    public function chooseController(
        array $modeParams,
        ?array $streamParams,
        ?string $controllerArg
    ): array {
        $controller = ucfirst($modeParams[self::CONTROLLER]);
        $controllerMode = ucfirst($modeParams[self::FUNC_MODE]);

        $controller .= $controllerMode;
        $controller = "App\Controllers\Console\\{$controller}Controller";
        if (!class_exists($controller)) {
            throw new Exception('Bad mode param' . PHP_EOL);
        }
        $controller = $this->di->get($controller);

        if ($controllerArg) {
            method_exists(
                $controller,
                'setArgument'
            ) ? $controller->setArgument($controllerArg) : null;
        }
        if (!isset($streamParams)) {
            return ['controller' => $controller];
        }

        $streamMode = [
            'Export' => 'Writer',
            'Import' => 'Reader',
        ];
        $streamMode = $streamMode[$controllerMode] ?? '';

        $streamName = ucfirst($streamParams[self::STREAM_MODE]);
        $writer = "App\Models\Service\\$streamMode\\{$streamName}$streamMode";
        if (!class_exists($writer)) {
            throw new Exception('Bad stream param' . PHP_EOL);
        }

        $writer = $this->di->get(
            $writer,
            ['fileName' => $streamParams[self::FILE_NAME]]
        );

        return [
            'controller' => $controller,
            'stream'     => $writer,
        ];
    }
}
