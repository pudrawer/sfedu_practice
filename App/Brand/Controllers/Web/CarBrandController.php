<?php

namespace App\Brand\Controllers\Web;

use App\Brand\Blocks\BrandBlock;
use App\Brand\Models\Resource\BrandResource;
use App\Core\Blocks\BlockInterface;
use App\Core\Controllers\Web\AbstractController;
use App\Core\Exception\Exception;
use App\Core\Models\Environment;
use App\Core\Models\Validator\Validator;
use Laminas\Di\Di;

class CarBrandController extends AbstractController
{
    public function __construct(
        Di $di,
        Environment $env,
        BrandResource $resource,
        BrandBlock $block,
        Validator $validator,
        array $params = []
    ) {
        parent::__construct(
            $di,
            $env,
            $params,
            $resource,
            $block,
            $validator
        );
    }

    public function execute(): BlockInterface
    {
        if ($this->isGetMethod()) {
            $brandParam = $this->getParams['brand'] ?? null;

            if (!$brandParam) {
                throw new Exception('Bad get param' . PHP_EOL);
            }

            $brand = $this->resource->getBrandInfo($brandParam);
            return $this->block
                ->setData($brand)
                ->setHeader([$brand->getName()])
                ->render('carInfo');
        }

        $this->validator->checkName($this->getPostParam('name'));

        $this->changeProperties([
            'id',
            'name',
            'countryId',
        ], 'brand');
        $this->redirectTo('carBrandList');
    }
}
