<?php

namespace App\Brand\Blocks;

use App\Core\Blocks\AbstractBlock;

class BrandListBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'brand-list.css',
    ];
    protected $data = [];
    protected $fileRender = 'car-brand-list';
}
