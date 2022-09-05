<?php

namespace App\Blocks;

class HomepageBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'description/description.css',
        'faq/faq.css',
        'faq/faq.css',
        'vehicle/vehicle.css',
    ];
    protected $fileRender = 'homepage';

    public function render(): self
    {
        parent::commonRender('main');

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
