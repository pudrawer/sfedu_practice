<?php

namespace App\Blocks;

interface BlockInterface
{
    public function render(string $activeLink): AbstractBlock;

    public function getData();

    public function setData($data);

    public function getHeader(string $separator): string;
}
