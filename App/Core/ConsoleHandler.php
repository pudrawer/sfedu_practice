<?php

namespace App\Core;

use App\Core;

class ConsoleHandler
{
    private static $instance;

    protected const ONE_DIMENSIONAL_ARRAY = 1;
    protected const TWO_DIMENSIONAL_ARRAY = 2;

    public function handle(array $argv)
    {
        $router = new Core\Router\ConsoleRouter();

        $modeParams = explode(':', $argv[1]);
        $streamParams = $argv[2] ?? null;

        $streamParams = strpos(
            $streamParams,
            ':'
        ) ? explode(':', $streamParams) : null;

        $controllerArg = null;
        foreach ($argv as $arg) {
            $needle = '--argument=';
            $temp = strpos($arg, $needle);

            if ($temp !== false) {
                $controllerArg = substr($arg, strlen($needle));
                break;
            }
        }

        $classList = $router->chooseController(
            is_array($modeParams) ? $modeParams : [],
            $streamParams,
            $controllerArg
        );

        $controller = $classList['controller'];
        $stream = $classList['stream'] ?? false;

        $result = $controller->execute();

        if ($stream) {
            $arrayMode = self::ONE_DIMENSIONAL_ARRAY;
            foreach ($result as $value) {
                if (is_array($value)) {
                    $arrayMode = self::TWO_DIMENSIONAL_ARRAY;
                    break;
                }
            }

            $stream->write($result, $arrayMode);
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance) {
            return self::$instance;
        }

        return self::$instance = new self();
    }
}
