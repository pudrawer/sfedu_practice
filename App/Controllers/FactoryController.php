<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\HeaderBlock;

class FactoryController extends AbstractController
{
    public function execute(): BlockInterface
    {
        echo 'Welcome to factory page';

        return new HeaderBlock();
    }
}
