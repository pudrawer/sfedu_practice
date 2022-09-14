<?php

namespace App\Blocks;

class NotFoundBlock extends AbstractBlock
{
    protected $fileRender = 'not-found';
    protected $childStylesheetList = ['error/error.css'];

    public function render(): self
    {
        $this->prepareRenderedPage('main');

        return $this;
    }
}
