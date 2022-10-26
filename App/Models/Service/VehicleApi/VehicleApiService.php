<?php

namespace App\Models\Service\VehicleApi;

class VehicleApiService
{
    protected const BRAND_ID_KEY   = 'Make_ID';
    protected const BRAND_NAME_KEY = 'Make_Name';

    public function prepareBrandArrayToCreate(array $data): array
    {
        $idData   = [];
        $nameData = [];

        $counter = 0;
        foreach ($data as $item) {
            $idData[":brand_id_$counter"] = $item[self::BRAND_ID_KEY];
            $nameData[":brand_name_$counter"] = $item[self::BRAND_NAME_KEY];

            $counter++;
        }

        return [
            'idData'   => $idData,
            'nameData' => $nameData,
        ];
    }
}
