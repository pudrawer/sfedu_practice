<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\WrongBlock;

class WrongController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $block = new WrongBlock();

        return $block
            ->setHeader(['page' => '500'])
            ->render($block->getActiveLink());
    }
}
