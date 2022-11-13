<?php

namespace App\Controllers\Web;

use App\Blocks\BlockInterface;
use App\Exception\Exception;
use App\Models\Environment;
use App\Models\Resource\ModelResource;
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
