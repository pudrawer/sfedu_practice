<?php

namespace App\Blocks;

class ModelBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'car-info/car-info.css',
        'info/info.css',
        'info-stat/info-stat.css',
    ];
    protected $fileRender = 'car-model';

    public function render(): self
    {
        parent::commonRender('carInfo');

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->header = [
            'brand'  => $data['brand'],
            'line'   => $data['line'],
            'model'  => $data['model'],
        ];

        foreach ($this->header as $key => $item) {
            unset($data[$key]);
        }

        $this->data = $data;

        return $this;
    }
}
