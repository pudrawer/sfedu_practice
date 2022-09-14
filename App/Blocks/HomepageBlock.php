<?php

namespace App\Blocks;

class HomepageBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'description/description.css',
        'faq/faq.css',
        'faq/faq.css',
        'vehicle/vehicle.css',
    ];
    protected $fileRender = 'homepage';
    protected $activeLink = 'main';
}
