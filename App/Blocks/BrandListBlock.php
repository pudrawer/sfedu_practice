<?php

namespace App\Blocks;

use App\Database\Database;

class BrandListBlock extends AbstractBlock
{
    protected $childStylesheetList = [
        'brand-list/brand-list.css',
    ];
    protected $data = [];
    protected $fileRender = 'car-brand-list';

    public function render(): self
    {
        parent::commonRender('carInfo');

        return $this;
    }

    public function setData(array $data): self
    {
        $this->header['page'] = $data['page'];
        unset($data['page']);

        $this->data = $data['data'];

        return $this;
    }
}