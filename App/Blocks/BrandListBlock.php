<?php

namespace App\Blocks;

use App\Database\Database;

class BrandListBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'brand-list/brand-list.css',
    ];
    protected $data = [];
    protected $fileRender = 'car-brand-list';

    public function render(): self
    {
        parent::prepareRenderedPage('carInfo');

        return $this;
    }
}
