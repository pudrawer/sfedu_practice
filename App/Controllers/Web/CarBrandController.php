<?php

namespace App\Controllers\Web;

use App\Blocks\BlockInterface;
use App\Blocks\BrandBlock;
use App\Exception\Exception;
use App\Models\Resource\BrandResource;
use App\Models\Validator\Validator;

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
            $brandResource = new BrandResource();

            $brand = $brandResource->getBrandInfo($brandParam);
            return $block
                ->setData($brand)
                ->setHeader([$brand->getName()])
                ->render('carInfo');
        }

        $validator = new Validator();
        $validator->checkName($this->getPostParam('name'));

        $this->changeProperties([
            'id',
            'name',
            'countryId',
        ], 'brand');
        $this->redirectTo('carBrandList');
    }
}
