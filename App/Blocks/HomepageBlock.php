<?php

namespace App\Blocks;

class HomepageBlock implements BlockInterface
{
    private $data = [];

    public function render(): self
    {
        require APP_ROOT . '/App/Views/homepage.phtml';

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
