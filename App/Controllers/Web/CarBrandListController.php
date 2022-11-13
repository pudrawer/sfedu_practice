<?php

namespace App\Controllers\Web;

use App\Blocks\AbstractBlock;
use App\Blocks\BlockInterface;
use App\Blocks\BrandListBlock;
use App\Models\Environment;
use App\Models\Randomizer\Randomizer;
use App\Models\Resource\AbstractResource;
use App\Models\Resource\BrandResource;
use App\Models\Service\AbstractService;
use App\Models\Session\Session;
use App\Models\Validator\Validator;
use Laminas\Di\Di;

class CarBrandListController extends AbstractController
{
    public function __construct(
        Di $di,
        Environment $env,
        BrandResource $resource,
        BrandListBlock $block,
        array $params = []
    ) {
        parent::__construct(
            $di,
            $env,
            $params,
            $resource,
            $block
        );
    }

    public function execute(): BlockInterface
    {
        $this->block
            ->setHeader(['BRAND LIST'])
            ->setData($this->resource->getBrandList())
            ->render('carInfo');

        return $this->block;
    }
}
