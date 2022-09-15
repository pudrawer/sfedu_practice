<?php

namespace App\Models\Selection;

interface SelectionInterface
{
    public function selectData(array $haystack): array;
}
