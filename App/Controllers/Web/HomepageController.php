<?php

namespace App\Controllers\Web;

use App\Blocks\BlockInterface;
use App\Blocks\HomepageBlock;

class HomepageController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $block = new HomepageBlock();
        return $block
            ->setHeader(['MAIN'])
            ->render('main');
    }
}
