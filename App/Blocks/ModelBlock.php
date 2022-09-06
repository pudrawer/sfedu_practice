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

    public function setData(array $data): self
    {
        $this->header = [
            'brand'  => $data['commonInfo']['brand'],
            'line'   => $data['commonInfo']['line'],
            'model'  => $data['commonInfo']['model'],
        ];

        foreach ($this->header as $key => $item) {
            unset($data['commonInfo'][$key]);
        }

        $this->data = $data;

        return $this;
    }
}
