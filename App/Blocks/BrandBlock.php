<?php

namespace App\Blocks;

class BrandBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'car-info/car-info.css',
        'common-info/common-info.css',
        'info/info.css',
        'info-stat/info-stat.css',
    ];

    protected $fileRender = 'car-brand';

    public function render(): self
    {
        parent::prepareRenderedPage('carInfo');

        return $this;
    }
}
