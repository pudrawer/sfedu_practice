<?php

namespace App\Blocks;

class LineBlock implements BlockInterface
{
    private $data = [];

    public function getData(): array
    {
        return $this->data;
    }

    public function render(): self
    {
        require APP_ROOT . '/App/Templates/layout.phtml';
        layout_render(
            $this->data['brand'] . $this->data['line'],
            'car-line',
            2,
            $this
        );

        return $this;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}