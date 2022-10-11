<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Exception\Exception;
use App\Models\Resource\BrandResource;

class CarBrandDeleteWebController extends AbstractWebController
{
    public function execute(): BlockInterface
    {
        $idParam = $this->getId();

        if (!$idParam) {
            throw new Exception();
        }

        $brandResource = new BrandResource();
        $brandResource->delete($idParam);

        $this->redirectTo('carBrandList');
    }
}
