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
            ->setHeader(['404'])
            ->render('main');

        return $block;
    }
}
