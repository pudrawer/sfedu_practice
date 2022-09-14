<?php

namespace App\Blocks;

class WrongBlock extends AbstractBlock
{
    protected $fileRender = 'something-wrong';
    protected $childStylesheetList = ['error/error.css'];

    public function render(): self
    {
        $this->prepareRenderedPage('main');

        return $this;
    }
}
