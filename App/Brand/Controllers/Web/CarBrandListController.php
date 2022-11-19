<?php

namespace App\Brand\Controllers\Web;

use App\Brand\Blocks\BrandListBlock;
use App\Brand\Models\Resource\BrandResource;
use App\Core\Blocks\BlockInterface;
use App\Core\Controllers\Web\AbstractController;
use App\Core\Models\Environment;
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
