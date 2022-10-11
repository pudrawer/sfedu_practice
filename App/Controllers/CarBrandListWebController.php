<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\BrandListBlock;
use App\Models\Resource\BrandRecourse;

class CarBrandListWebController extends AbstractWebController
{
    public function execute(): BlockInterface
    {
        $block = new BrandListBlock();
        $model = new BrandRecourse();
        $block
            ->setHeader(['BRAND LIST'])
            ->setData($model->getBrandList())
            ->render('carInfo');

        return $block;
    }
}
