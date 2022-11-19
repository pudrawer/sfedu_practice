<?php

namespace App\Car\Controllers\Web;

use App\Core\Blocks\BlockInterface;
use App\Core\Blocks\HeaderBlock;
use App\Core\Controllers\Web\AbstractController;

class FactoryController extends AbstractController
{
    public function execute(): BlockInterface
    {
        echo 'Welcome to factory page';

        return new HeaderBlock();
    }
}
