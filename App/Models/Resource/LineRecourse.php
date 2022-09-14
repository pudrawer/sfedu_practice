<?php

namespace App\Models\Resource;

use App\Exception\Exception;
use App\Models\AbstractCarModel;
use App\Database\Database;

class LineRecourse extends AbstractResource
{
    public function getLineInfo(
        int $brandId,
        int $lineId
    ): array {
        $connection = Database::getInstance();

        $stmt = $connection->prepare('
        SELECT
            cl.*,
            cb.`name` as brand_name,
            country.`name` as country_name,
            country.`id` as country_id
        FROM car_line cl
            JOIN car_brand cb
                on cl.car_brand_id = cb.id
            JOIN country
                on cb.country_id = country.id
        WHERE cb.id = :brand_id AND cl.id = :line_id LIMIT 1;
        ');

        $idParamMap = [
            ':brand_id' => $brandId,
            ':line_id' => $lineId,
        ];
        foreach ($idParamMap as $alias => &$value) {
            $stmt->bindParam(
                $alias,
                $value,
                \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT
            );
        }

        $stmt->execute();

        $lineInfo = $stmt->fetch();
        if (!$lineInfo) {
            throw new Exception('Data not found' . PHP_EOL);
        }

        $data = $this->brandSelection($this->prepareKeyMap($LineInfo));

        return [
            'brandModel' => $data['model'],
            'data' => $this->prepareValueSimpleMap(array_merge(
                $data['data'],
                ['modelList' => $this->getModelList($lineId)]
            ), 'line')
        ];
    }

    public function getModelList(int $lineId): array
    {
        $connection = Database::getInstance();

        $stmt = $connection->prepare('
        SELECT
            car_model.id,
            car_model.`name`
        FROM car_model
        JOIN car_line cl 
            on car_model.car_line_id = cl.id
        WHERE car_model.car_line_id=?;
        ');
        $stmt->bindParam(
            1,
            $lineId,
            \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT
        );
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
