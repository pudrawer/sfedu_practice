<?php

namespace App\Blocks;

class ForbiddenBlock extends AbstractBlock
{
    protected $fileRender = 'forbidden';
    protected $childStylesheetList = ['error.css'];
}
