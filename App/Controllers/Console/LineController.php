<?php

namespace App\Controllers\Console;

class LineController implements \App\Controllers\ControllerInterface
{
    public function execute(): array
    {
        $lineResource = new \App\Models\Resource\LineResource();
        $brandResource = new \App\Models\Resource\BrandResource();

        $lineList = $lineResource->getInformation();
        foreach ($lineList as &$line) {
            $brandInfo = $brandResource->getById($line['car_brand_id']);

            $line['brand_name'] = $brandInfo['name'];
            $line['country_id'] = $brandInfo['country_id'];
        }

        return $lineList;
    }

    public function __toString(): string
    {
        return 'line';
    }
}
