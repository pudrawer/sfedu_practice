<?php

namespace App\SpecificCar\Controllers\Web;

use App\Core\Controllers\Web\AbstractController;

class CarController extends AbstractController
{
    public function execute(): void
    {
        echo 'Welcome to car page';
    }
}
