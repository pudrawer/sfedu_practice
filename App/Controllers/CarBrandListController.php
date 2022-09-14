<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\BrandListBlock;
use App\Models\Recourse\BrandRecourse;

class CarBrandListController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $block = new BrandListBlock();
        $model = new BrandRecourse();
        $block
            ->setHeader(['page' => 'BRAND LIST'])
            ->setData($model->getBrandList())
            ->render($block->getActiveLink());

        return $block;
    }
}
