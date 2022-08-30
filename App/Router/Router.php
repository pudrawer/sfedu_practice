<?php

namespace App\Router;

use App\Controllers\CarBrandController;
use App\Controllers\CarController;
use App\Controllers\CarLineController;
use App\Controllers\CarModelController;
use App\Controllers\CountryController;
use App\Controllers\FactoryController;

class Router
{
    public function chooseController(string $filePath): void
    {
        $controller = false;

        switch ($filePath) {
            case '/': {
                echo 'Welcome to homepage';
                break;
            }

            case '/car': {
                $controller = new CarController();
                $controller->listAction();

                break;
            }

            case '/factory': {
                $controller = new FactoryController();
                $controller->listAction();

                break;
            }

            case '/carBrand': {
                $controller = new CarBrandController();
                $controller->listAction();

                break;
            }

            case '/carModel': {
                $controller = new CarModelController();
                $controller->listAction();

                break;
            }

            case '/carLine': {
                $controller = new CarLineController();
                $controller->listAction();

                break;
            }

            case '/country': {
                $controller = new CountryController();
                $controller->listAction();

                break;
            }

            default: {
                echo '404';
                break;
            }
        }
    }
}