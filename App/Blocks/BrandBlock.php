<?php

namespace App\Blocks;

class BrandBlock extends AbstractBlock
{
    protected $fileRender = 'car-brand';

    public function setData(array $data): self
    {
        $tempData = $data;
        $this->header = [
            'brand' => $tempData['brand'],
        ];

        foreach ($this->header as $key => $item) {
            unset($tempData[$key]);
        }

        $this->data = $tempData;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function render(): self
    {
        $styleBlock = new StylesheetBlock();
        $headerBlock = new HeaderBlock();
        $footerBlock = new FooterBlock();

        $footerBlock->setData([
            'quickLinks' => [
                'main',
                'main',
                'main',
                'main',
            ],
            'pageLinks' => [
                'main',
                'main',
                'main',
                'main',
            ],
        ]);

        $styleBlock
            ->setData(array_slice(scandir($this->srcPath), 2))
            ->render()
        ;
        $headerBlock->setData([
            'activeLink' => 'carInfo'
        ]);

        require "$this->viewsPath/Components/layout.phtml";

        return $this;
    }
}
