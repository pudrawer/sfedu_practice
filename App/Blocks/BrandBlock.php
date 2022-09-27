<?php

namespace App\Blocks;

class BrandBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'car-info.css',
        'common-info.css',
        'info.css',
        'info-stat.css',
    ];
    protected $fileRender = 'car-brand';
}
