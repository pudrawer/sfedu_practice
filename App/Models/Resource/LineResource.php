<?php

namespace App\Models\Resource;

use App\Exception\ResourceException;
use App\Models\AbstractCarModel;
use App\Database\Database;
use App\Models\Line;
use App\Models\Selection\BrandSelection;

class LineResource extends AbstractResource
{
    public function getLineInfo(
        int $brandId,
        int $lineId
    ): array {
        $stmt = $this->database->getPdo()->prepare('
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
            throw new ResourceException('Data not found' . PHP_EOL);
        }

        $brandSelection = $this->di->get(BrandSelection::class);
        $data = $brandSelection->selectData(
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
        $stmt = $this->database->getPdo()->prepare('
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

    public function getByBrandId(int $brandId): ?array
    {
        $stmt = $this->database->getPdo()->prepare('
        SELECT 
            `id`, 
            `name` 
        FROM 
            `car_line` 
        WHERE `car_brand_id` = :brand_id;
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':brand_id' => $brandId,
        ]);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param Line $model
     * @return bool
     * @throws ResourceException
     */
    public function modifyProperties(AbstractCarModel $model): bool
    {
        $stmt = $this->database->getPdo()->prepare('
        UPDATE
            `car_line`
        SET
            `name` = :line_name
        WHERE `id` = :line_id LIMIT 1;
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':line_name' => $model->getName(),
            ':line_id' => $model->getId(),
        ]);

        if (!$stmt->execute()) {
            throw new ResourceException('Query error' . PHP_EOL);
        }

        return true;
    }

    public function delete(int $id): bool
    {
        $lineModel = $this->di->get(Line::class);
        $lineModel->setId($id);

        $this->deleteEntity($lineModel, 'car_model', 'car_line_id');
        return $this->deleteEntity($lineModel, 'car_line', 'id');
    }

    public function getById(int $id): Line
    {
        $stmt = $this->database->getPdo()->prepare('
        SELECT
            *
        FROM
            `car_line`
        WHERE `id` = :line_id LIMIT 1;
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':line_id' => $id,
        ]);
        $stmt->execute();

        $result = $stmt->fetch();
        if (!$result) {
            throw new ResourceException();
        }

        $model = $this->di->get(Line::class);
        return $model
            ->setName($result['name'])
            ->setBrandId($result['car_brand_id']);
    }

    public function createEntity(Line $model): Line
    {
        $stmt = $this->database->getPdo()->prepare('
        INSERT INTO 
            `car_line` (`name`, `car_brand_id`)
        VALUES 
            (:line_name, :brand_id)
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':line_name' => $model->getName(),
            ':brand_id'  => $model->getBrandId(),
        ]);

        if ($stmt->execute()) {
            return $this->di->get($model);
        }

        throw new ResourceException();
    }

    public function modifyAllProperties(Line $model): Line
    {
        $stmt = Database::getInstance()->prepare('
        UPDATE
            `car_line`
        SET
            `id` = :modified_id,
            `name` = :modified_name,
            `car_brand_id` = :modified_brand_id
        WHERE `id` = :line_id LIMIT 1;
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':modified_id'       => $model->getModifiedId(),
            ':modified_name'     => $model->getName(),
            ':modified_brand_id' => $model->getBrandId(),
            ':line_id'           => $model->getId(),
        ]);

        if ($stmt->execute()) {
            return $model;
        }

        throw new ResourceException();
    }

    public function getInformation(): array
    {
        return parent::getAllInformation('car_line');
    }
}
