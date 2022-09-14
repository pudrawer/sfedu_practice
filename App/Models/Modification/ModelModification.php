<?php

namespace App\Models\Modification;

use App\Database\Database;
use App\Exception\Exception;
use App\Models\AbstractCarModel;
use App\Models\Model;

class ModelModification extends AbstractCarModification
{
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

        $stmt = $this->bindParamByMap($stmt, $this->prepareParamMap($model));

        if (!$stmt->execute()) {
            throw new Exception('Query error' . PHP_EOL);
        }

        return true;
    }

    /**
     * @param Model $model
     * @return array
     */
    public function prepareParamMap(AbstractCarModel $model): array
    {
        return [
            ':model_name'  => $model->getName(),
            ':model_year'  => $model->getYear(),
            ':previous_id' => $model->getPreviousId(),
            ':model_id'    => $model->getId(),
        ];
    }
}
