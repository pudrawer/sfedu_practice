<?php

namespace App\Models\Selection;

interface SelectionInterface
{
    public static function selectData(array $haystack): array;
}
