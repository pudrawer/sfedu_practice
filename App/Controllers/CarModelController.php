<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\ModelBlock;
use App\Exception\Exception;
use App\Models\Model;
use App\Models\Recourse\ModelRecourse;

class CarModelController extends AbstractController
{
    public function execute(): BlockInterface
    {
        if ($this->isGetMethod()) {
            $brandParam = $this->getParams['brand'] ?? null;
            $lineParam = $this->getParams['line'] ?? null;
            $modelParam = $this->getParams['model'] ?? null;

            if (!$brandParam || !$lineParam || !$modelParam) {
                throw new Exception('Bad get param' . PHP_EOL);
            }

            $block = new ModelBlock();
            $modelResource = new ModelRecourse();
            $data = $modelResource->getModelInfo($brandParam, $lineParam, $modelParam);
            $block
                ->setHeader([
                    $data['brandModel']->getName(),
                    $data['lineModel']->getName(),
                    $data['data']->getName()
                ])
                ->setChildModels($data['brandModel'])
                ->setChildModels($data['lineModel'])
                ->setData($data['data'])
                ->render('carInfo');

            return $block;
        }

        $this->changeProperties([
            'id',
            'name',
            'year',
            'previousId',
        ], 'model');
        $this->redirectTo('carBrandList');
    }
}
