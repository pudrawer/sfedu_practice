<?php

namespace App\Models\Recourse;

use App\Exception\Exception;
use App\Models\AbstractCarModel;
use App\Database\Database;
use App\Models\Brand;
use App\Models\Line;
use App\Models\Selection\BrandSelection;
use App\Models\Selection\SelectionInterface;

class LineRecourse extends AbstractRecourse
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

        $stmt = $this->bindParamByMap($stmt, [
            ':brand_id' => $brandId,
            ':line_id' => $lineId,
        ]);

        $stmt->execute();

        $lineInfo = $stmt->fetch();
        if (!$lineInfo) {
            throw new Exception('Data not found' . PHP_EOL);
        }


        $data = BrandSelection::selectData(
            $this->prepareKeyMap($lineInfo)
        );

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
        WHERE car_model.car_line_id = :car_line_id;
        ');
        $stmt = $this->bindParamByMap($stmt, [
            ':car_line_id' => $lineId,
        ]);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param Line $model
     * @return bool
     * @throws Exception
     */
    public function modifyProperties(AbstractCarModel $model): bool
    {
        $connection = Database::getInstance();
        $stmt = $connection->prepare('
        UPDATE
            `car_line`
        SET
            `name` = :line_name
        WHERE `id` = :line_id LIMIT 1;
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':line_name' => $model->getName(),
            ':line_id'   => $model->getId(),
        ]);

        if (!$stmt->execute()) {
            throw new Exception('Query error' . PHP_EOL);
        }

        return true;
    }
}
