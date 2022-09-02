<?php

namespace App\Blocks;

class FooterBlock extends AbstractBlock
{
    protected $data = [
        'quickLinks' => [],
        'pageLinks' => [],
    ];

    public function render(): self
    {
        require "$this->viewsPath/Components/footer.phtml";

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
