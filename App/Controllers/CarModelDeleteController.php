<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Exception\Exception;
use App\Models\Recourse\ModelRecourse;

class CarModelDeleteController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $idParam = $this->getId();

        if (!$idParam) {
            throw new Exception();
        }

        $modelResource = new ModelRecourse();
        $modelResource->delete($idParam);
        $this->redirectTo('carBrandList');
    }
}