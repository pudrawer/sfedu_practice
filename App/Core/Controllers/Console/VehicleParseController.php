<?php

namespace App\Core\Controllers\Console;

use App\Brand\Models\Resource\BrandResource;
use App\Core\Exception\Exception;
use App\Core\Models\ExternalApi\Vehicle;
use App\Core\Models\Service\VehicleApiService;

class VehicleParseController implements \App\Core\Controllers\ControllerInterface
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
