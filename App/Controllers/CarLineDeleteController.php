<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Exception\Exception;
use App\Models\Recourse\LineRecourse;

class CarLineDeleteController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $idParam = htmlspecialchars($this->getParams['id'] ?? '');

        if (!$idParam) {
            throw new Exception();
        }

        $lineResource = new LineRecourse();
        $lineResource->deleteNote($idParam);
        $this->redirectTo('carBrandList');
    }
}
