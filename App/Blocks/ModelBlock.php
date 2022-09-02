<?php

namespace App\Blocks;

class ModelBlock implements BlockInterface
{
    private $data = [];

    public function render(): self
    {
        require APP_ROOT . '/App/Templates/layout.phtml';
        layout_render(
            $this->data['brand'] . $this->data['line'] . $this->data['model'],
            'car-model',
            2,
            $this
        );

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}