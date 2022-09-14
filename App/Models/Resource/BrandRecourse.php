<?php

namespace App\Models\Resource;

use App\Database\Database;
use App\Exception\Exception;
use App\Models\AbstractCarModel;
use App\Models\BrandModel;

class BrandRecourse extends AbstractResource
{
    /**
     * @return BrandModel[]
     */
    public function getBrandList(): ?array
    {
        $connection = Database::getInstance();
        $stmt = $connection->prepare('SELECT `name`, `id` FROM `car_brand`;');
        $stmt->execute();

        return $this->prepareValueMap($stmt->fetchAll(), 'brand');
    }

    /**
     * @param int $brandId
     * @return BrandModel
     */
    public function getBrandInfo(int $brandId): AbstractCarModel
    {
        $connection = Database::getInstance();
        $stmt = $connection->prepare('
        SELECT 
            car_brand.*, 
            country.`name` as country_name 
        FROM car_brand 
            JOIN country 
                on car_brand.country_id = country.id 
        WHERE car_brand.id=:brand_id LIMIT 1;
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':brand_id' => $brandId,
        ]);
        $stmt->execute();
        $brandInfo = $stmt->fetch();
        if (!$brandInfo) {
            throw new Exception('Data not found' . PHP_EOL);
        }

        return $this->prepareValueSimpleMap(
            array_merge(
                $this->prepareKeyMap($brandInfo),
                ['lineList' => $this->getLineList($brandId)]
            ),
            'brand'
        );
    }

    private function getLineList(int $brandId): ?array
    {
        $connection = Database::getInstance();

        $stmt = $connection->prepare('
        SELECT id, name FROM car_line WHERE car_brand_id = :brand_id;
        ');
        $stmt = $this->bindParamByMap($stmt, [
            ':brand_id' => $brandId,
        ]);
        $stmt->execute();

        return $stmt->fetchAll();
    }

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

        $stmt = $this->bindParamByMap($stmt, [
            ':brand_name' => $model->getName(),
            ':country_id' => $model->getCountryId(),
            ':brand_id'   => $model->getId(),
        ]);

        if (!$stmt->execute()) {
            throw new Exception('Query error' . PHP_EOL);
        }

        return true;
    }
}
