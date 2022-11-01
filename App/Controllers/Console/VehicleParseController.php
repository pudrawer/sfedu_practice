<?php

namespace App\Controllers\Console;

use App\Exception\Exception;
use App\Models\ExternalApi\Vehicle;
use App\Models\Resource\BrandResource;
use App\Models\Service\VehicleApiService;

class VehicleParseController implements \App\Controllers\ControllerInterface
{
    protected const REQUEST_WEB_PATH = 'getallmakes';

    public function execute()
    {
        $vehicleApi = new Vehicle();

        if (!$vehicleApi->requestData(self::REQUEST_WEB_PATH)) {
            throw new Exception('Bad request param' . PHP_EOL);
        }

        $brandResource = new BrandResource();
        $vehicleApiService = new VehicleApiService();

        $temp = array_map(
            [$vehicleApiService, 'mapApiResult'],
            $vehicleApi->getResult()
        );

        $brandResource->createByData($temp);
    }
}
