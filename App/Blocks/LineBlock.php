<?php

namespace App\Blocks;

class LineBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'car-info/car-info.css',
        'common-info/common-info.css',
        'info/info.css',
        'info-stat/info-stat.css',
    ];
    protected $fileRender = 'car-line';

    public function render(): self
    {
        parent::commonRender('carInfo');

        return $this;
    }

    public function setData(array $data): self
    {
        $this->header = [
            'brand' => $data['commonInfo']['brand_name'],
            'line'  => $data['commonInfo']['name'],
        ];

        foreach ($this->header as $key => $item) {
            unset($data['commonInfo'][$key]);
        }

        $this->data = $data;

        return $this;
    }
}
