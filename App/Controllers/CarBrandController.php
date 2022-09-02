<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\BrandBlock;

class CarBrandController implements ControllerInterface
{
    public function execute(): BlockInterface
    {
        $block = new BrandBlock();

        return $block->setData([
            'brand' => 'BMW',
            'country' => 'Germany',
            'brandLine' => [
                'M2', 'M3', 'M4', 'M5', 'M8',
            ]
        ])->render();
    }
}
