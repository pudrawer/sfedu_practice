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

        $stmt->bindParam(
            ':brand_id',
            $brandId,
            \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT
        );
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
        $stmt->bindParam(
            ':brand_id',
            $brandId,
            \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT
        );
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
