<?php

namespace App\Controllers\Web;

use App\Blocks\BlockInterface;
use App\Exception\Exception;
use App\Models\Resource\LineResource;

class CarLineDeleteController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $idParam = $this->getId();

        if (!$idParam) {
            throw new Exception();
        }

        $lineResource = new LineResource();
        $lineResource->delete($idParam);
        $this->redirectTo('carBrandList');
    }
}
