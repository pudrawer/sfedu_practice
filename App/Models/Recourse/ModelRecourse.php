<?php

namespace App\Models\Recourse;

use App\Database\Database;
use App\Exception\Exception;
use App\Models\AbstractCarModel;
use App\Models\Brand;
use App\Models\Line;
use App\Models\Model;

class ModelRecourse extends AbstractRecourse
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

        $stmt = $this->bindParamByMap($stmt, [
            ':brand_id' => $brandId,
            ':line_id' => $lineId,
            ':model_id' => $modelId,
        ]);

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
        $model = $this->prepareValueSimpleMap($data['data'], 'model');

        return [
            'brandModel' => $brandModel,
            'lineModel'  => $lineModel,
            'data'       => $model,
        ];
    }

    /**
     * @param Model $model
     * @return bool
     * @throws Exception
     */
    public function modifyProperties(AbstractCarModel $model): bool
    {
        $connection = Database::getInstance();
        $stmt = $connection->prepare('
        UPDATE
            `car_model`
        SET
            `name` = :model_name,
            `year` = :model_year,
            `previous_line_model` = :previous_id
        WHERE `id` = :model_id LIMIT 1;
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':model_name'  => $model->getName(),
            ':model_year'  => $model->getYear(),
            ':previous_id' => $model->getPreviousId(),
            ':model_id'    => $model->getId(),
        ]);

        if (!$stmt->execute()) {
            throw new Exception('Query error' . PHP_EOL);
        }

        return true;
    }

    protected function brandSelection(array $haystack): ?array
    {
        $haveBrand = (bool)$haystack['brandName'];

        if ($haveBrand) {
            $brand = new Brand();
            $brand
                ->setId($haystack['carBrandId'])
                ->setName($haystack['brandName'])
                ->setCountryName($haystack['countryName'])
                ->setCountryId($haystack['countryId'])
            ;

            unset($haystack['carBrandId']);
            unset($haystack['brandName']);
            unset($haystack['countryName']);
            unset($haystack['countryId']);

            return ['model' => $brand, 'data' => $haystack];
        }

        throw new Exception();
    }

    protected function lineSelection(array $haystack): ?array
    {
        $haveLine = (bool)$haystack['lineName'];

        if ($haveLine) {
            $line = new Line();
            $line
                ->setId($haystack['lineId'])
                ->setName($haystack['lineName'])
            ;

            unset($haystack['lineId']);
            unset($haystack['lineName']);

            return ['model' => $line, 'data' => $haystack];
        }

        throw new Exception();
    }
}
