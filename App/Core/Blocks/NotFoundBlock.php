<?php

namespace App\Core\Blocks;

class NotFoundBlock extends AbstractBlock
{
    protected $fileRender = 'not-found';
    protected $childStylesheetList = ['error.css'];
}
