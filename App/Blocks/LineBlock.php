<?php

namespace App\Blocks;

use App\Models\AbstractCarModel;

class LineBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'car-info/car-info.css',
        'common-info/common-info.css',
        'info/info.css',
        'info-stat/info-stat.css',
    ];
    protected $fileRender = 'car-line';
    protected $activeLink = 'carInfo';

    protected $childModels = [];

    public function setChildModels(AbstractCarModel $model): self
    {
        $this->childModels["$model"] = $model;

        return $this;
    }

    public function getChildModels(): array
    {
        return $this->childModels;
    }
}
