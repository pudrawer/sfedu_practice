<?php

namespace App\Core\Models\Resource;

use App\Core\Database\Database;
use App\Core\Exception\ResourceException;
use App\Core\Models\AbstractCarModel;
use Laminas\Di\Di;

abstract class AbstractResource
{
    protected $di;
    protected $database;

    public function __construct(Di $di, Database $database)
    {
        $this->di       = $di;
        $this->database = $database;
    }

    protected function prepareValueSimpleMap(
        array $data,
        string $model
    ): AbstractCarModel {
        $tempModel = $this->di->get($model);
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

        foreach ($data as $item) {
            $tempModel = $this->di->newInstance($model);

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
        $stmt = $this->database->getPdo()->prepare("
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

        $stmt = $this->database->getPdo()->prepare("
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
        $stmt = $this->database->getPdo()->prepare("
        SELECT * FROM $tableName;
        ");
        $stmt->execute();

        $result = $stmt->fetchAll();
        if (is_array($result)) {
            return $result;
        }

        throw new ResourceException();
    }

    public function preparedValuesSql(array $data, string $sample = '(?)'): string
    {
        $preparedPlaceholder = array_fill(0, count($data), $sample);

        return implode(',' . PHP_EOL, $preparedPlaceholder);
    }
}
