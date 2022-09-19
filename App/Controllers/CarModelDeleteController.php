<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Exception\Exception;
use App\Models\Recourse\ModelRecourse;

class CarModelDeleteController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $idParam = htmlspecialchars($this->getParams['id'] ?? '');

        if (!$idParam) {
            throw new Exception();
        }

        $modelResource = new ModelRecourse();
        $modelResource->deleteNote($idParam);
        $this->redirectTo('carBrandList');
    }
}
