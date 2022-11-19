<?php

namespace App\Core\Blocks;

class HeaderBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'header.css',
        'nav.css',
        'wish-list.css',
    ];

    private $linkStatus = [
        'main'        => '',
        'carInfo'     => '',
        'specificCar' => '',
        'factory'     => '',
        'country'     => '',
    ];

    public function renderCommonBlock(): self
    {
        require "$this->viewsPath/Components/header.phtml";

        return $this;
    }

    public function getData(): array
    {
        return $this->linkStatus;
    }

    public function setActiveLink(string $link): self
    {
        if ($this->linkStatus[$link] == '') {
            $this->linkStatus[$link] = 'nav__link_active';
        }

        return $this;
    }
}
