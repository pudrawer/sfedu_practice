<?php

namespace App\Api\Controllers;

class NotFoundApiController
{
    public function execute()
    {
        header('Content-Type: application/json', true, 404);
    }
}
