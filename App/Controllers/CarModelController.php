<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\ModelBlock;
use App\Database\Database;

class CarModelController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $brandParam = $this->getParams['brand'] ?? null;
        $lineParam  = $this->getParams['line'] ?? null;
        $modelParam = $this->getParams['model'] ?? null;

        if (!$brandParam || !$lineParam || !$modelParam) {
            $this->redirectTo('carBrandList');
        }

        $block = new ModelBlock();

        $result = $this->prepareKeyMap($this->getModelInfo(
            $brandParam,
            $lineParam,
            $modelParam
        ));

        return $block
            ->setHeader([
                'brandName' => $result['brandName'],
                'lineName'  => $result['lineName'],
                'modelName' => $result['name'],
            ])
            ->setData([
                'countryName'  => $result['countryName'],
                'modelYear'    => $result['year'],
                'previousName' => $result['previousName'],
                'previousId'   => $result['previousId'],
                'brandId'      => $result['brandId'],
                'lineId'       => $result['lineId'],
                'modelId'      => $result['id'],
            ])->render();
    }

    private function getModelInfo(
        int $brandId,
        int $lineId,
        int $modelId
    ): ?array {
        $connection = Database::getConnection();
        $stmt = $connection->prepare('
        SELECT
            cb.name as brand_name,
            cb.id as brand_id,
            cl.name as line_name,
            cl.id as line_id,
            cm.id,
            cm.name,
            cm.year,
            cm.previous_line_model as previous_id,
            country.name as country_name,
            previous.name as  previous_name
        FROM car_model cm
            JOIN car_line cl
                ON cm.car_line_id = cl.id
            JOIN car_brand cb
                ON cl.car_brand_id = cb.id
            JOIN country
                ON country.id = cb.country_id
            JOIN car_model previous
                ON previous.id = cm.previous_line_model
        WHERE 
            car_brand_id = :brand_id 
            AND cm.car_line_id = :line_id 
            AND cm.id = :model_id 
        LIMIT 1;
        ');

        $idParamMap = [
            ':brand_id' => $brandId,
            ':line_id' => $lineId,
            ':model_id' => $modelId,
        ];
        foreach ($idParamMap as $alias => &$value) {
            $stmt->bindParam(
                $alias,
                $value,
                \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT
            );
        }

        $stmt->execute();

        return $stmt->fetch();
    }
}
