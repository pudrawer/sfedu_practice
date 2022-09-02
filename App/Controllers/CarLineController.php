<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\LineBlock;

class CarLineController implements ControllerInterface
{
    public function execute(): BlockInterface
    {
        $block = new LineBlock();

        return $block->setData([
            'brand'     => 'BMW',
            'country'   => 'Germany',
            'line'      => 'M8',
            'modelLine' => [
                'M8', 'M8 competition'
            ]
        ])->render();
    }
}
