<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Exception\Exception;
use App\Models\Recourse\BrandRecourse;

class CarBrandDeleteController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $idParam = $this->getId();

        if (!$idParam) {
            throw new Exception();
        }

        $brandResource = new BrandRecourse();
        $brandResource->delete($idParam);

        $this->redirectTo('carBrandList');
    }
}
