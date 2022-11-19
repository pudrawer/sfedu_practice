<?php

namespace App\CarModel\Blocks;

use App\Core\Blocks\AbstractBlock;
use App\Core\Models\AbstractCarModel;

class ModelBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'car-info.css',
        'info.css',
        'info-stat.css',
    ];
    protected $fileRender = 'car-model';

    protected $childModels = [];

    public function setChildModels(AbstractCarModel $model): self
    {
        $this->childModels[(string) $model] = $model;

        return $this;
    }

    public function getChildModels(): array
    {
        return $this->childModels;
    }
}
