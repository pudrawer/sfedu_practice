<?php

namespace App\Models\Resource;

use App\Database\Database;
use App\Exception\ResourceException;
use App\Models\AbstractCarModel;
use App\Models\Brand;
use App\Models\Line;

class BrandResource extends AbstractResource
{
    /**
     * @param Brand $model
     * @return bool
     */
    public function createNewEntity(AbstractCarModel $model): bool
    {
        $stmt = Database::getInstance()->prepare('
        INSERT INTO
            car_brand (`name`, `country_id`)
        VALUES 
            (:brand_name, :country_id);
        ');
        $stmt = $this->bindParamByMap($stmt, [
            ':brand_name' => $model->getName(),
            ':country_id' => $model->getCountryId(),
        ]);

        return $stmt->execute();
    }

    /**
     * @return Brand[]
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
     * @return Brand
     * @throws ResourceException
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
            throw new ResourceException('Data not found' . PHP_EOL);
        }

        $lineResource = new LineResource();
        return $this->prepareValueSimpleMap(
            array_merge(
                $this->prepareKeyMap($brandInfo),
                ['lineList' => $lineResource->getByBrandId($brandId)]
            ),
            'brand'
        );
    }

    /**
     * @param Brand $model
     * @return bool
     * @throws ResourceException
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
            throw new ResourceException('Query error' . PHP_EOL);
        }

        return true;
    }

    public function modifyAllProperties(
        AbstractCarModel $model
    ): bool {
        $stmt = Database::getInstance()->prepare('
        UPDATE
            `car_brand`
        SET
            `id` = :modified_id,
            `name` = :modified_name,
            `country_id` = :modified_country
        WHERE
            `id` = :brand_id LIMIT 1;
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':modified_id'      => $model->getModifiedId(),
            ':modified_name'    => $model->getName(),
            ':modified_country' => $model->getCountryId(),
            ':brand_id'         => $model->getId(),
        ]);
        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $brandModel = new Brand();
        $brandModel->setId($id);

        $connection = Database::getInstance();
        $stmt = $connection->prepare('
        SELECT 
            id 
        FROM 
            `car_line` 
        WHERE 
            `car_brand_id` = :car_brand_id;
        ');
        $stmt = $this->bindParamByMap($stmt, [
            ':car_brand_id' => $brandModel->getId(),
        ]);
        $stmt->execute();

        $lineList = $stmt->fetchAll();
        foreach ($lineList as &$line) {
            $lineModel = new Line();
            $lineModel->setId($line['id']);

            $line = $lineModel;
        }

        $this->deleteEntityList($lineList, 'car_model', 'car_line_id');
        $this->deleteEntity($brandModel, 'car_line', 'car_brand_id');
        return $this->deleteEntity($brandModel, 'car_brand', 'id');
    }

    public function getInformation(): array
    {
        return parent::getAllInformation('car_brand');
    }

    public function getById(int $id): array
    {
        $stmt = Database::getInstance()->prepare('
        SELECT
            *
        FROM
            `car_brand`
        WHERE `id` = :brand_id LIMIT 1;
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':brand_id' => $id,
        ]);
        $stmt->execute();

        $result = $stmt->fetch();
        if (!$result) {
            throw new ResourceException();
        }

        return $result;
    }
}
