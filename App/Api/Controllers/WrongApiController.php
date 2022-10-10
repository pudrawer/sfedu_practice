<?php

namespace App\Api\Controllers;

class WrongApiController
{
    public function execute()
    {
        header('Content-Type: application/json', true, 500);
    }
}
