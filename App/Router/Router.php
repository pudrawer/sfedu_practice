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
    public function chooseController(string $webPath)
    {
        switch ($webPath) {
            case '/': {
                echo 'Welcome to homepage';
                return false;
            }

            case '/car': {
                return new CarController();
            }

            case '/factory': {
                return new FactoryController();
            }

            case '/carBrand': {
                return new CarBrandController();
            }

            case '/carModel': {
                return new CarModelController();
            }

            case '/carLine': {
                return new CarLineController();
            }

            case '/country': {
                return new CountryController();
            }

            default: {
                return new NotFoundController();
            }
        }
    }
}
