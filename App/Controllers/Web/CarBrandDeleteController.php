<?php

namespace App\Controllers\Web;

use App\Blocks\BlockInterface;
use App\Exception\Exception;
use App\Models\Environment;
use App\Models\Resource\BrandResource;
use Laminas\Di\Di;

class CarBrandDeleteController extends AbstractController
{
    public function __construct(
        Di $di,
        Environment $env,
        BrandResource $resource,
        array $params
    ) {
        parent::__construct(
            $di,
            $env,
            $params,
            $resource
        );
    }

    public function execute(): BlockInterface
    {
        $idParam = $this->getId();

        if (!$idParam) {
            throw new Exception();
        }

        $this->resource->delete($idParam);

        $this->redirectTo('carBrandList');
    }
}
