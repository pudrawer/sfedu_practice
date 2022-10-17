<?php

namespace App\Controllers\Api;

class WrongApiController
{
    public function execute(int $response_code = 404)
    {
        header('Content-Type: application/json', true, $response_code);
    }
}
