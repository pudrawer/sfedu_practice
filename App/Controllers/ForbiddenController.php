<?php

namespace App\Controllers;

use App\Blocks\ForbiddenBlock;
use App\Blocks\BlockInterface;

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
