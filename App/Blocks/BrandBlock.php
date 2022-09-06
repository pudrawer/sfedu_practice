<?php

namespace App\Blocks;

class BrandBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'car-info/car-info.css',
        'common-info/common-info.css',
        'info/info.css',
        'info-stat/info-stat.css',
    ];

    protected $fileRender = 'car-brand';

    public function setData(array $data): self
    {
        $this->header = [
            'brand' => $data['commonInfo']['name'],
        ];

        foreach ($this->header as $key => $item) {
            unset($data[$key]);
        }

        $this->data = $data;

        return $this;
    }

    public function render(): self
    {
        parent::commonRender('carInfo');

        return $this;
    }
}
