<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\LineBlock;
use App\Models\LineModel;
use App\Models\Modification\LineModification;
use App\Models\Resource\LineRecourse;
use App\Exception\Exception;

class CarLineController extends AbstractController
{
    public function execute(): BlockInterface
    {
        if (REQUEST_METHOD == 'GET') {
            $brandParam = $this->getParams['brand'] ?? null;
            $lineParam = $this->getParams['line'] ?? null;

            if (!$brandParam || !$lineParam) {
                throw new Exception('Bad get param' . PHP_EOL);
            }

            $block = new LineBlock();
            $model = new LineRecourse();

            $data = $model->getLineInfo($brandParam, $lineParam);
            $block
                ->setChildModels($data['brandModel'])
                ->setData($data['data'])
                ->setHeader([
                    $data['brandModel']->getName(),
                    $block->getData()->getName()
                ])
                ->render();

            return $block;
        }

        $this->changeProperties();
        $this->redirectTo('carBrandList');
    }

    public function changeProperties(): bool
    {
        $idParam   = htmlspecialchars($_POST['lineId']) ?? null;
        $nameParam = htmlspecialchars($_POST['lineName']) ?? null;

        if (!$idParam || !$nameParam) {
            throw new Exception('Bad post params' . PHP_EOL);
        }

        $model = new LineModel();
        $model
            ->setId($idParam)
            ->setName($nameParam);

        $modificator = new LineModification();
        return $modificator->modifyProperties($model);
    }
}
