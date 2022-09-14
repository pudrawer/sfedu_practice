<?php

namespace App\Blocks;

interface BlockInterface
{
    public function render();

    public function getData();

    public function setData($data);

    public function getHeader(string $separator): string;
}
