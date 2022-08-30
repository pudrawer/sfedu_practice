<?php

namespace App\Router;

use App\Controllers\CarBrandController;
use App\Controllers\CarController;
use App\Controllers\CarLineController;
use App\Controllers\CarModelController;
use App\Controllers\CountryController;
use App\Controllers\FactoryController;
use App\Controllers\NotFoundController;

class Router
{
    public function chooseController(string $filePath)
    {
        $controller = false;

        switch ($filePath) {
            case '/': {
                echo 'Welcome to homepage';
                break;
            }

            case '/car': {
                $controller = new CarController();
                break;
            }

            case '/factory': {
                $controller = new FactoryController();
                break;
            }

            case '/carBrand': {
                $controller = new CarBrandController();
                break;
            }

            case '/carModel': {
                $controller = new CarModelController();
                break;
            }

            case '/carLine': {
                $controller = new CarLineController();
                break;
            }

            case '/country': {
                $controller = new CountryController();
                break;
            }

            default: {
                $controller = new NotFoundController();
                break;
            }
        }

        return $controller;
    }
}
