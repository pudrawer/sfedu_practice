<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\ModelBlock;
use App\Exception\Exception;
use App\Models\Model;
use App\Models\Modification\ModelModification;
use App\Models\Resource\ModelRecourse;

class CarModelController extends AbstractController
{
    public function execute(): BlockInterface
    {
        if (REQUEST_METHOD == 'GET') {
            $brandParam = $this->getParams['brand'] ?? null;
            $lineParam = $this->getParams['line'] ?? null;
            $modelParam = $this->getParams['model'] ?? null;

            if (!$brandParam || !$lineParam || !$modelParam) {
                throw new Exception('Bad get param' . PHP_EOL);
            }

            $block = new ModelBlock();
            $model = new ModelRecourse();
            $data = $model->getModelInfo($brandParam, $lineParam, $modelParam);
            $block
                ->setHeader([
                    $data['brandModel']->getName(),
                    $data['lineModel']->getName(),
                    $data['data']->getName()
                ])
                ->setChildModels($data['brandModel'])
                ->setChildModels($data['lineModel'])
                ->setData($data['data'])
                ->render();

            return $block;
        }

        $this->changeProperties();
        $this->redirectTo('carBrandList');
    }

    public function changeProperties(): bool
    {
        $idParam   = htmlspecialchars($_POST['modelId']) ?? null;
        $nameParam = htmlspecialchars($_POST['modelName']) ?? null;
        $yearParam = htmlspecialchars($_POST['modelYear']) ?? null;
        $previousParam = htmlspecialchars($_POST['previousId']) ?? null;

        if (
            !$idParam
            || !$nameParam
            || !$yearParam
            || !$previousParam
        ) {
            throw new Exception('Bad post params' . PHP_EOL);
        }

        $model = new Model();
        $model
            ->setId($idParam)
            ->setName($nameParam)
            ->setYear($yearParam)
            ->setPreviousId($previousParam);

        $modificator = new ModelModification();
        return $modificator->modifyProperties($model);
    }
}
