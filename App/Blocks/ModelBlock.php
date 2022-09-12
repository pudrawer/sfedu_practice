<?php

namespace App\Blocks;

class ModelBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'car-info/car-info.css',
        'info/info.css',
        'info-stat/info-stat.css',
    ];
    protected $fileRender = 'car-model';

    public function render(): self
    {
        parent::prepareRenderedPage('carInfo');

        return $this;
    }
}
