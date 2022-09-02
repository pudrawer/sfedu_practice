<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\HomepageBlock;

class HomepageController implements ControllerInterface
{

    public function execute(): BlockInterface
    {
        $block = new HomepageBlock();
        return $block->setData([])->render();
    }
}