<?php

namespace App\Api\Controllers;

class ForbiddenApiController
{
    public function execute()
    {
        header('Content-Type: application/json', true, 403);
    }
}
