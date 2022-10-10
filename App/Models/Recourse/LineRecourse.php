<?php

namespace App\Models\Recourse;

use App\Exception\RecourseException;
use App\Models\AbstractCarModel;
use App\Database\Database;
use App\Models\Line;
use App\Models\Selection\BrandSelection;

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
            throw new RecourseException('Data not found' . PHP_EOL);
        }

        $brandSelection = new BrandSelection();
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
     * @throws RecourseException
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
            ':line_id' => $model->getId(),
        ]);

        if (!$stmt->execute()) {
            throw new RecourseException('Query error' . PHP_EOL);
        }

        return true;
    }

    public function delete(int $id): bool
    {
        $lineModel = new Line();
        $lineModel->setId($id);

        $this->deleteEntity($lineModel, 'car_model', 'car_line_id');
        return $this->deleteEntity($lineModel, 'car_line', 'id');
    }

    public function getLinesByHttp(): array
    {
        $stmt = Database::getInstance()->prepare('
        SELECT
            `id`,
            `name`,
            `car_brand_id`
        FROM 
            `car_line`
        ');

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getLineByHttp(Line $model): Line
    {
        $stmt = Database::getInstance()->prepare('
        SELECT
            *
        FROM
            `car_line`
        WHERE `id` = :line_id LIMIT 1;
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':line_id' => $model->getId(),
        ]);
        $stmt->execute();

        $result = $stmt->fetch();
        if (!$result) {
            throw new RecourseException();
        }

        return $model
            ->setName($result['name'])
            ->setBrandId($result['car_brand_id']);
    }

    public function createEntity(Line $model): Line
    {
        $stmt = Database::getInstance()->prepare('
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
            return $model;
        }

        throw new RecourseException();
    }

    public function modifyPropertiesByHttp(Line $model): Line
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

        throw new RecourseException();
    }
}
