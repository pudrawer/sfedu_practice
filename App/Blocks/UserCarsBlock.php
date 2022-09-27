<?php

namespace App\Blocks;

use App\Models\AbstractCarModel;
use App\Models\Car;

class UserCarsBlock extends AbstractBlock
{
    protected $fileRender = 'user-cars';
    protected $childStylesheetList = [
        'cars.css',
        'profile-nav.css',
    ];

    protected $childCars = [];

    /**
     * @param  Car $carList
     * @return $this
     */
    public function setChildCar(Car $carList): self
    {
        $this->childCars[] = $carList;

        return $this;
    }

    /**
     * @return Car[]
     */
    public function getChildCars(): array
    {
        return $this->childCars;
    }
}
