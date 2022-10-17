<?php

namespace App\Controllers\Web;

use App\Blocks\BlockInterface;
use App\Blocks\BrandListBlock;
use App\Models\Resource\BrandResource;

class CarBrandListController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $block = new BrandListBlock();
        $model = new BrandResource();
        $block
            ->setHeader(['BRAND LIST'])
            ->setData($model->getBrandList())
            ->render('carInfo');

        return $block;
    }
}
