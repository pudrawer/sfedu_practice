<?php

namespace App\Controllers\Web;

use App\Blocks\BlockInterface;
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
