<?php

namespace App\Api\Controllers;

class WrongApiController
{
    public function execute(int $response_code = 404)
    {
        header('Content-Type: application/json', true, $response_code);
    }
}
