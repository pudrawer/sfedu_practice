<?php

namespace App\Blocks;

class BrandBlock implements BlockInterface
{
    private $data = [];

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function render(): self
    {
        require_once APP_ROOT . '/App/Templates/layout.phtml';
        layout_render(
            $this->data['brand'],
            'car-brand',
            2,
            $this
        );

        return $this;
    }
}