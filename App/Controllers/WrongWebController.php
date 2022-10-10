<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\WrongBlock;

class WrongWebController extends AbstractWebController
{
    public function execute(): BlockInterface
    {
        $block = new WrongBlock();

        return $block
            ->setHeader(['500'])
            ->render('main');
    }
}
