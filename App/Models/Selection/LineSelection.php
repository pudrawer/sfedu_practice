<?php

namespace App\Models\Selection;

use App\Exception\Exception;
use App\Exception\SelectionException;
use App\Models\Line;

class LineSelection extends AbstractSelection
{
    public function selectData(array $haystack): array
    {
        $hasNeededData = $haystack['lineName'] && $haystack['lineId'];

        if ($hasNeededData) {
            $line = $this->di->get(Line::class);
            $line
                ->setId($haystack['lineId'])
                ->setName($haystack['lineName']);

            unset($haystack['lineId']);
            unset($haystack['lineName']);

            return ['model' => $line, 'data' => $haystack];
        }

        throw new SelectionException('Bad selection data' . PHP_EOL);
    }
}
