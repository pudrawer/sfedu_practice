<?php

namespace App\Controllers\Console;

use App\Exception\Exception;
use App\Models\Resource\BrandResource;
use App\Models\Service\VehicleApi\VehicleApiService;
use App\Models\Validator\Validator;
use GuzzleHttp;

class VehicleParseController implements \App\Controllers\ControllerInterface
{
    protected const API_WEB_PATH = 'https://vpic.nhtsa.dot.gov/api/vehicles/';

    public function execute()
    {
        $client = new GuzzleHttp\Client([
            'base_uri' => self::API_WEB_PATH,
        ]);

        $response = $client->request('GET', 'getallmakes?format=json');

        $validator = new Validator();
        $brandResource = new BrandResource();
        $vehicleApiService = new VehicleApiService();

        $temp = array_map(
            [$vehicleApiService, 'mappingApiResult'],
            $validator->validateApiResponse($response->getBody())
        );

        $brandResource->createByData($temp);
    }
}
