<?php

namespace App\Blocks;

class HomepageBlock extends AbstractBlock
{
    protected $fileRender = 'homepage';

    public function render(): self
    {
        $headerBlock = new HeaderBlock();
        $styleBlock = new StylesheetBlock();
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
        $headerBlock->setData(['activeLink' => 'main']);
        $styleBlock
            ->setData(array_slice(scandir($this->srcPath), 2))
            ->render()
        ;

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
            'page' => $tempData['page'],
        ];

        foreach ($this->header as $key => $item) {
            unset($tempData[$key]);
        }

        $this->data = $tempData;

        return $this;
    }
}
