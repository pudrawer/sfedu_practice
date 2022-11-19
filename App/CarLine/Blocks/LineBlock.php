<?php

namespace App\CarLine\Blocks;

use App\Core\Blocks\AbstractBlock;
use App\Core\Models\AbstractCarModel;

class LineBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'car-info.css',
        'common-info.css',
        'info.css',
        'info-stat.css',
    ];
    protected $fileRender = 'car-line';

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
