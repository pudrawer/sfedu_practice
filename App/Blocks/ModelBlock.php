<?php

namespace App\Blocks;

use App\Models\AbstractCarModel;

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
