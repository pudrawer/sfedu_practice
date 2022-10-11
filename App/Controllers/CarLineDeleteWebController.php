<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Exception\Exception;
use App\Models\Resource\LineRecourse;

class CarLineDeleteWebController extends AbstractWebController
{
    public function execute(): BlockInterface
    {
        $idParam = $this->getId();

        if (!$idParam) {
            throw new Exception();
        }

        $lineResource = new LineRecourse();
        $lineResource->delete($idParam);
        $this->redirectTo('carBrandList');
    }
}
