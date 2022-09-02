<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\BrandBlock;

class NotFoundController implements ControllerInterface
{
    public function execute(): BlockInterface
    {
        echo '404 - Page not found';

        return new BrandBlock();
    }
}
