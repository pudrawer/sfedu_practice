<?php

namespace App\Models\Service\VehicleApi;

use App\Models\Brand;

class VehicleApiService
{
    protected const BRAND_ID_KEY   = 'Make_ID';
    protected const BRAND_NAME_KEY = 'Make_Name';

    public function mappingApiResult(array $data): Brand
    {
        $temp = new Brand();

        return $temp
            ->setId($data[self::BRAND_ID_KEY])
            ->setName($data[self::BRAND_NAME_KEY]);
    }
}
