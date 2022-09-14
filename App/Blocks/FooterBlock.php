<?php

namespace App\Blocks;

class FooterBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'footer/footer.css',
    ];

    protected $data = [
        'quickLinks' => [],
        'pageLinks' => [],
    ];

    public function renderCommonBlock(): self
    {
        require "$this->viewsPath/Components/footer.phtml";

        return $this;
    }
}
