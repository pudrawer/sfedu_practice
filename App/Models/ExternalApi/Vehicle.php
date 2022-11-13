<?php

namespace App\Models\ExternalApi;

use GuzzleHttp;

class Vehicle
{
    protected const API_WEB_PATH   = 'https://vpic.nhtsa.dot.gov/api/vehicles/';
    protected const API_POSTFIX    = '?format=json';
    protected const API_RESULT_KEY = 'Result';

    protected $data = [];
    protected $client = null;

    public function __construct()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => self::API_WEB_PATH,
        ]);
    }

    public function requestData(string $webPath): bool
    {
        $response = $this->client->request('GET', $webPath . self::API_POSTFIX);

        if ($response->getStatusCode() === 200) {
            $this->data = json_decode($response->getBody(), true);

            return (bool) $this->data;
        }

        return false;
    }

    public function getResult(): array
    {
        return $this->data[self::API_RESULT_KEY] ?? [];
    }
}
