<?php

namespace App\Blocks;

class LineBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'car-info/car-info.css',
        'common-info/common-info.css',
        'info/info.css',
        'info-stat/info-stat.css',
    ];
    protected $fileRender = 'car-line';

    public function render(): self
    {
        parent::prepareRenderedPage('carInfo');

        return $this;
    }
}
