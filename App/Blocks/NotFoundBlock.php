<?php

namespace App\Blocks;

class NotFoundBlock extends AbstractBlock
{
    protected $fileRender = 'not-found';
    protected $childStylesheetList = ['error/error.css'];
    protected $activeLink = 'main';
}
