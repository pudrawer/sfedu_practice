<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\HeaderBlock;

class FactoryWebController extends AbstractWebController
{
    public function execute(): BlockInterface
    {
        echo 'Welcome to factory page';

        return new HeaderBlock();
    }
}
