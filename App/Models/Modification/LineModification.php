<?php

namespace App\Models\Modification;

use App\Database\Database;
use App\Exception\Exception;
use App\Models\AbstractCarModel;
use App\Models\LineModel;

class LineModification extends AbstractCarModification
{
    /**
     * @param LineModel $model
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

        $stmt = $this->bindParamByMap($stmt, $this->prepareParamMap($model));

        if (!$stmt->execute()) {
            throw new Exception('Query error' . PHP_EOL);
        }

        return true;
    }

    /**
     * @param LineModel $model
     * @return array
     */
    public function prepareParamMap(AbstractCarModel $model): array
    {
        return [
            ':line_name' => $model->getName(),
            ':line_id'   => $model->getId(),
        ];
    }
}
