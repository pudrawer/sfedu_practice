<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\HomepageBlock;

class HomepageController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $block = new HomepageBlock();
        return $block->setData(['page' => 'MAIN'])->render();
    }
}
