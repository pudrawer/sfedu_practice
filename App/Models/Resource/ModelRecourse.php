<?php

namespace App\Models\Resource;

use App\Database\Database;
use App\Exception\ResourceException;
use App\Models\AbstractCarModel;
use App\Models\Model;
use App\Models\Selection\LineSelection;
use App\Models\Selection\BrandSelection;

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
            throw new ResourceException('Data not found' . PHP_EOL);
        }

        return $this->splitByModel($modelInfo);
    }

    private function splitByModel(array $data): array
    {
        $selection = new BrandSelection();
        $data = $selection->selectData($this->prepareKeyMap($data));
        $brandModel = $data['model'];

        $selection = new LineSelection();
        $data = $selection->selectData($data['data']);
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
     * @throws ResourceException
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
            throw new ResourceException('Query error' . PHP_EOL);
        }

        return true;
    }

    public function delete(int $id): bool
    {
        $model = new Model();
        $model->setId($id);

        return $this->deleteEntity($model, 'car_model', 'id');
    }

    public function getOnlyModelInfo(int $id): Model
    {
        $stmt = Database::getInstance()->prepare('
        SELECT
            *
        FROM
            `car_model`
        WHERE `id` = :model_id;
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':model_id' => $id,
        ]);
        $stmt->execute();

        $result = $stmt->fetch();
        if (!$result) {
            throw new ResourceException();
        }

        $model = new Model();
        return $model
            ->setId($id)
            ->setName($result['name'])
            ->setYear($result['year'])
            ->setPreviousId($result['previous_line_model'])
            ->setLineId($result['car_line_id']);
    }

    public function modifyAllProperties(Model $model): Model
    {
        $stmt = Database::getInstance()->prepare('
        UPDATE
            `car_model`
        SET
            `id` = :modified_id,
            `name` = :modified_name,
            `car_line_id` = :modified_car_line_id,
            `previous_line_model` = :modified_previous_model,
            `year` = :modified_year
        WHERE `id` = :model_id LIMIT 1;
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':modified_id'             => $model->getModifiedId(),
            ':modified_name'           => $model->getName(),
            ':modified_car_line_id'    => $model->getLineId(),
            ':modified_previous_model' => $model->getPreviousId(),
            ':modified_year'           => $model->getYear(),
            ':model_id'                => $model->getId(),
        ]);

        if ($stmt->execute()) {
            return $model;
        }

        throw new ResourceException();
    }

    public function createEntity(Model $model): Model
    {
        $stmt = Database::getInstance()->prepare('
        INSERT INTO
            `car_model` (`name`, `previous_line_model`, `year`, `car_line_id`)
        VALUES 
            (:model_name, :previous_line, :model_year, :car_line_id);
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':model_name'    => $model->getName(),
            ':previous_line' => $model->getPreviousId(),
            ':model_year'    => $model->getYear(),
            ':car_line_id'   => $model->getLineId(),
        ]);

        if (!$stmt->execute()) {
            throw new ResourceException();
        }

        return $model;
    }
}
