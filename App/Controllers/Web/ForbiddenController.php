<?php

namespace App\Controllers\Web;

use App\Blocks\BlockInterface;
use App\Blocks\ForbiddenBlock;

class ForbiddenController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $block = new ForbiddenBlock();
        return $block
            ->setHeader(['403'])
            ->render('main');
    }
}
