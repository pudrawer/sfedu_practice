<?php

namespace App\Controllers\Web;

use App\Blocks\AbstractBlock;
use App\Blocks\BlockInterface;
use App\Blocks\BrandBlock;
use App\Exception\Exception;
use App\Models\Environment;
use App\Models\Randomizer\Randomizer;
use App\Models\Resource\AbstractResource;
use App\Models\Resource\BrandResource;
use App\Models\Service\AbstractService;
use App\Models\Session\Session;
use App\Models\Validator\Validator;
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
