<?php

namespace App\Controllers\Console;

use App\Exception\Exception;
use App\Models\Resource\BrandResource;
use App\Models\Service\VehicleApi\VehicleApiService;
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
        $response = $response->getBody();

        $result = json_decode($response->getContents(), true);
        if ($result['Count'] === 0) {
            throw new Exception('Bad request data' . PHP_EOL);
        }

        $result = $result['Results'];

        $brandResource = new BrandResource();
        $vehicleApiService = new VehicleApiService();

        $brandResource->createNewEntityByArray(
            $vehicleApiService->prepareBrandArrayToCreate($result)
        );
    }
}
