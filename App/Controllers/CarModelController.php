<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\ModelBlock;

class CarModelController implements ControllerInterface
{
    public function execute(): BlockInterface
    {
        $block = new ModelBlock();
        return $block->setData([
            'brand' => 'BMW',
            'line' => 'M8',
            'model' => 'competition',
            'country' => 'Germany',
            'year' => '2021',
            'previous' => 'competition',
        ])->render();
    }
}
