<?php

namespace App\Blocks;

use App\Models\AbstractCarModel;

class UserCarsBlock extends AbstractBlock
{
    protected $fileRender = 'user-cars';
    protected $activeLink = 'specificCar';
    protected $childStylesheetList = [
        'cars/cars.css',
        'profile-nav/profile-nav.css',
    ];

    protected $childModels = [];

    /**
     * @param  AbstractCarModel[] $modelList
     * @return $this
     */
    public function setChildModelsList(array $modelList): self
    {
        $this->childModels[] = $modelList;

        return $this;
    }

    /**
     * @return AbstractCarModel[]
     */
    public function getChildModelsList(): array
    {
        return $this->childModels;
    }
}
