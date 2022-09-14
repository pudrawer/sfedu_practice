<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\BrandBlock;
use App\Exception\Exception;
use App\Models\Recourse\BrandRecourse;

class CarBrandController extends AbstractController
{
    public function execute(): BlockInterface
    {
        if ($this->isGetMethod()) {
            $brandParam = $this->getParams['brand'] ?? null;

            if (!$brandParam) {
                throw new Exception('Bad get param' . PHP_EOL);
            }

            $block = new BrandBlock();
            $brandResource = new BrandRecourse();

            $brand = $brandResource->getBrandInfo($brandParam);
            return $block
                ->setData($brand)
                ->setHeader([$brand->getName()])
                ->render($block->getActiveLink());
        }

        $this->changeProperties([
            'id',
            'name',
            'countryId'
        ], 'brand');
        $this->redirectTo('carBrandList');
    }
}
