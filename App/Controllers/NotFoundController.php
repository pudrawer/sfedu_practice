<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\BrandBlock;
use App\Blocks\NotFoundBlock;

class NotFoundController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $block = new NotFoundBlock();
        $block
            ->setHeader(['page' => '404'])
            ->render($block->getActiveLink());

        return $block;
    }
}
