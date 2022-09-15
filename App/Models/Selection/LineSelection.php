<?php

namespace App\Models\Selection;

use App\Exception\Exception;
use App\Models\Line;

class LineSelection implements SelectionInterface
{
    public static function selectData(array $haystack): array
    {
        $hasNeededData = $haystack['lineName'] && $haystack['lineId'];

        if ($hasNeededData) {
            $line = new Line();
            $line
                ->setId($haystack['lineId'])
                ->setName($haystack['lineName']);

            unset($haystack['lineId']);
            unset($haystack['lineName']);

            return ['model' => $line, 'data' => $haystack];
        }

        throw new Exception();
    }
}