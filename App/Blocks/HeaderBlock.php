<?php

namespace App\Blocks;

class HeaderBlock extends AbstractBlock
{
    private $linkStatus = [
        'main'        => '',
        'carInfo'     => '',
        'specificCar' => '',
        'factory'     => '',
        'country'     => '',
    ];

    public function render(): self
    {
        require "$this->viewsPath/Components/header.phtml";

        return $this;
    }

    public function getData(): array
    {
        return $this->linkStatus;
    }

    public function setData(array $data): self
    {
        if ($this->linkStatus[$data['activeLink']] == '') {
            $this->linkStatus[$data['activeLink']] = 'nav__link_active';
        }

        return $this;
    }
}
