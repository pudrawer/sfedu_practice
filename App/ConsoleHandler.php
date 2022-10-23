<?php

namespace App;

class ConsoleHandler
{
    private static $instance;

    protected const ONE_DIMENSIONAL_ARRAY = 1;
    protected const TWO_DIMENSIONAL_ARRAY = 2;

    public function handle(array $argv)
    {
        $router = new \App\Router\ConsoleRouter();

        $modeParams = explode(':', $argv[1]);
        $streamParams = isset($argv[2]) ? explode(':', $argv[2]) : null;
        $classList = $router->chooseController(
            is_array($modeParams) ? $modeParams : [],
            $streamParams
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
