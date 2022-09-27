<?php

namespace App\Blocks;

class NotFoundBlock extends AbstractBlock
{
    protected $fileRender = 'not-found';
    protected $childStylesheetList = ['error.css'];
}
