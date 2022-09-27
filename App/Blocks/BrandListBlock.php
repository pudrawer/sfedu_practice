<?php

namespace App\Blocks;

use App\Database\Database;

class BrandListBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'brand-list.css',
    ];
    protected $data = [];
    protected $fileRender = 'car-brand-list';
}
