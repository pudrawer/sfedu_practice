<?php

namespace App\Controllers;

class NotFoundController implements ControllerInterface
{
    public function listAction(): void
    {
        echo '404 - Page not found';
    }
}
