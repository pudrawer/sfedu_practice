<?php

namespace App\Models\Service;

use App\Exception\Exception;
use App\Models\Brand;

class VehicleApiService extends AbstractService
{
    protected const BRAND_ID_KEY   = 'Make_ID';
    protected const BRAND_NAME_KEY = 'Make_Name';

    public function mapApiResult(array $data): Brand
    {
        $temp = $this->di->get(Brand::class);

        if (
            !is_int($data[self::BRAND_ID_KEY])
            || !is_string($data[self::BRAND_NAME_KEY])
        ) {
            throw new Exception('Type error' . PHP_EOL);
        }

        return $temp
            ->setId($data[self::BRAND_ID_KEY])
            ->setName($data[self::BRAND_NAME_KEY]);
    }
}
