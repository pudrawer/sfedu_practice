<?php

namespace App\CarModel\Controllers\Web;

use App\CarModel\Models\Resource\ModelResource;
use App\Core\Blocks\BlockInterface;
use App\Core\Controllers\Web\AbstractController;
use App\Core\Exception\Exception;
use App\Core\Models\Environment;
use Laminas\Di\Di;

class CarModelDeleteController extends AbstractController
{
    public function __construct(
        Di $di,
        array $params,
        Environment $env,
        ModelResource $resource
    ) {
        parent::__construct($di, $env, $params, $resource);
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
