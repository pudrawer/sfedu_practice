<?php

namespace App\Models\Resource;

use App\Database\Database;
use App\Exception\ResourceException;
use App\Models\AbstractCarModel;

abstract class AbstractResource
{
    protected function prepareValueSimpleMap(
        array $data,
        string $model
    ): AbstractCarModel {
        $model = '\App\Models\\' . ucfirst($model);

        $tempModel = new $model();
        foreach ($data as $key => $value) {
            $modelParam = 'set' . ucfirst($key);
            $tempModel->$modelParam($value);
        }

        return $tempModel;
    }

    protected function prepareValueMap(
        array $data,
        string $model = ''
    ): array {
        $result = [];
        $model = '\App\Models\\' . ucfirst($model);

        foreach ($data as $item) {
            $tempModel = new $model();

            foreach ($item as $key => $value) {
                $modelParam = 'set' . ucfirst($key);
                $tempModel->$modelParam($value);
            }

            $result[] = $tempModel;
        }

        return $result;
    }

    protected function prepareKeyMap(
        array $haystack = []
    ): array {
        $tempKeys = array_keys($haystack);

        foreach ($tempKeys as &$value) {
            $tempValue = explode('_', $value);
            $firstKeyWord = array_shift($tempValue);

            foreach ($tempValue as &$keyWord) {
                $keyWord = ucfirst($keyWord);
            }

            $value = $firstKeyWord . implode('', $tempValue);
        }

        return array_combine($tempKeys, $haystack);
    }

    public function bindParamByMap(
        \PDOStatement $stmt,
        array $paramMap
    ): \PDOStatement {
        foreach ($paramMap as $alias => &$value) {
            $stmt->bindParam(
                $alias,
                $value,
                \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT
            );
        }

        return $stmt;
    }

    protected function deleteEntity(
        AbstractCarModel $model,
        string $tableName,
        string $tableRow
    ): bool {
        $connection = Database::getInstance();
        $stmt = $connection->prepare("
        DELETE FROM 
            $tableName 
        WHERE 
            $tableRow = :entity_value;
        ");

        $stmt = $this->bindParamByMap($stmt, [
            ':entity_value' => $model->getId(),
        ]);

        if (!$stmt->execute()) {
            throw new ResourceException();
        }

        return true;
    }

    /**
     * @param AbstractCarModel[] $modelList
     * @param string $tableName
     * @param string $tableRow
     * @return bool
     * @throws ResourceException
     */
    protected function deleteEntityList(
        array $modelList,
        string $tableName,
        string $tableRow
    ): bool {
        if (!$modelList) {
            return true;
        }

        $valueStr = '';
        $valueAlias = [];
        foreach ($modelList as $model) {
            $valueNum = count($valueAlias);
            $value = ":row_value_$valueNum";

            if ($valueNum == 0) {
                $valueStr .= $value;
            } else {
                $valueStr .= ", $value";
            }

            $valueAlias[$value] = $model->getId();
        }

        $stmt = Database::getInstance()->prepare("
        DELETE FROM
            $tableName
        WHERE 
            $tableRow IN ($valueStr);
        ");
        $stmt = $this->bindParamByMap($stmt, $valueAlias);

        if (!$stmt->execute()) {
            throw new ResourceException();
        }

        return true;
    }

    public function getAllInformation(string $tableName): array
    {
        $stmt = Database::getInstance()->prepare("
        SELECT * FROM $tableName;
        ");
        $stmt->execute();

        $result = $stmt->fetchAll();
        if (is_array($result)) {
            return $result;
        }

        throw new RecourseException();
    }
}
