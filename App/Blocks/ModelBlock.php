<?php

namespace App\Blocks;

use App\Models\AbstractCarModel;

class ModelBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'car-info/car-info.css',
        'info/info.css',
        'info-stat/info-stat.css',
    ];
    protected $fileRender = 'car-model';

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

    public function render(): self
    {
        parent::prepareRenderedPage('carInfo');

        return $this;
    }
}
