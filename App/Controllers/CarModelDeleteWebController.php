<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Exception\Exception;
use App\Models\Resource\ModelResource;

class CarModelDeleteWebController extends AbstractWebController
{
    public function execute(): BlockInterface
    {
        $idParam = $this->getId();

        if (!$idParam) {
            throw new Exception();
        }

        $modelResource = new ModelResource();
        $modelResource->delete($idParam);
        $this->redirectTo('carBrandList');
    }
}
