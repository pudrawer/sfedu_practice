<?php

namespace App\Brand\Blocks;

use App\Core\Blocks\AbstractBlock;

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
