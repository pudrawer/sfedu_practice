<?php

namespace App\Blocks;

class ModelBlock extends AbstractBlock
{
    protected $fileRender = 'car-model';

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

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $tempData = $data;
        $this->header = [
            'brand'  => $tempData['brand'],
            'line'   => $tempData['line'],
            'model'  => $tempData['model'],
        ];

        foreach ($this->header as $key => $item) {
            unset($tempData[$key]);
        }

        $this->data = $tempData;

        return $this;
    }
}
