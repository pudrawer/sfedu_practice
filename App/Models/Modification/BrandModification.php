<?php

namespace App\Models\Modification;

use App\Database\Database;
use App\Exception\Exception;
use App\Models\AbstractCarModel;
use App\Models\BrandModel;

class BrandModification extends AbstractCarModification
{
    /**
     * @param BrandModel $model
     * @return bool
     * @throws Exception
     */
    public function modifyProperties(AbstractCarModel $model): bool
    {
        $connection = Database::getInstance();
        $stmt = $connection->prepare('
        UPDATE
            `car_brand`
        SET
            `name` = :brand_name,
            `country_id` = :country_id
        WHERE `id` = :brand_id LIMIT 1;
        ');

        $stmt = $this->bindParamByMap($stmt, $this->prepareParamMap($model));

        if (!$stmt->execute()) {
            throw new Exception('Query error' . PHP_EOL);
        }

        return true;
    }

    /**
     * @param BrandModel $model
     * @return array
     */
    public function prepareParamMap(AbstractCarModel $model): array
    {
        return [
            ':brand_name' => $model->getName(),
            ':country_id' => $model->getCountryId(),
            ':brand_id' => $model->getId(),
        ];
    }
}
