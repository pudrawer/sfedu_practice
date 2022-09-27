<?php

namespace App\Blocks;

class WrongBlock extends AbstractBlock
{
    protected $fileRender = 'something-wrong';
    protected $childStylesheetList = ['error.css'];
}
