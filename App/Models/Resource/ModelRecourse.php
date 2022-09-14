<?php

namespace App\Models\Resource;

use App\Database\Database;
use App\Exception\Exception;

class ModelRecourse extends AbstractResource
{
    public function getModelInfo(
        int $brandId,
        int $lineId,
        int $modelId
    ): array {
        $connection = Database::getInstance();
        $stmt = $connection->prepare('
        SELECT
            cb.name as brand_name,
            cb.id as car_brand_id,
            cl.name as line_name,
            cl.id as line_id,
            cm.id,
            cm.name,
            cm.year,
            cm.previous_line_model as previous_id,
            country.name as country_name,
            country.id as country_id,
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
        $modelInfo = $stmt->fetch();
        if (!$modelInfo) {
            throw new Exception('Data not found' . PHP_EOL);
        }

        return $this->splitByModel($modelInfo);
    }

    private function splitByModel(array $data): array
    {
        $data = $this->brandSelection($this->prepareKeyMap($data));
        $brandModel = $data['model'];
        $data = $this->lineSelection($data['data']);
        $lineModel = $data['model'];
        $model = $this->prepareValueSimpleMap($data['data']);

        return [
            'brandModel' => $brandModel,
            'lineModel'  => $lineModel,
            'data'       => $model,
        ];
    }
}
