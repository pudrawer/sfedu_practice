<?php

namespace App\Blocks;

interface BlockInterface
{
    public function render();

    public function getData(): array;

    public function setData(array $data);

    public function getHeader(): string;
}
