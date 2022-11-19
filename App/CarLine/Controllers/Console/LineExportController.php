<?php

namespace App\CarLine\Controllers\Console;

class LineExportController implements \App\Core\Controllers\ControllerInterface
{
    public function execute(): array
    {
        $lineResource = new \App\CarLine\Models\Resource\LineResource();
        $brandResource = new \App\Brand\Models\Resource\BrandResource();

        $lineList = $lineResource->getInformation();
        foreach ($lineList as &$line) {
            $brandInfo = $brandResource->getById($line['car_brand_id']);

            $line['brand_name'] = $brandInfo['name'];
            $line['country_id'] = $brandInfo['country_id'];
        }

        return $lineList;
    }
}
