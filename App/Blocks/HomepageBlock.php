<?php

namespace App\Blocks;

class HomepageBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'description.css',
        'faq.css',
        'faq.css',
        'vehicle.css',
    ];
    protected $fileRender = 'homepage';
}
