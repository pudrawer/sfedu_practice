<?php

namespace App\Brand\Controllers\Web;

use App\Brand\Models\Resource\BrandResource;
use App\Core\Blocks\BlockInterface;
use App\Core\Controllers\Web\AbstractController;
use App\Core\Exception\Exception;
use App\Core\Models\Environment;
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
