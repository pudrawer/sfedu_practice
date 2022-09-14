<?php

namespace App;

use App\Controllers\NotFoundController;
use App\Controllers\WrongController;
use App\Exception\Exception;
use App\Router\Router;

class PageHandler
{
    private static $instance;

    public function handlePage()
    {
        set_error_handler(function (
            int $errno,
            string $errstr,
            string $errfile
        ) {
            $controller = new WrongController();
            $controller->execute();

            echo $errstr;

            return true;
        }, E_ALL);

        try {
            $router = new Router();
            $controller = $router->chooseController($_SERVER['REQUEST_URI'] ?? '');

            $controller->execute();
        } catch (Exception $e) {
            $controller = new NotFoundController();
            $controller->execute();
        } catch (\Exception $e) {
            $controller = new WrongController();
            $controller->execute();
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
