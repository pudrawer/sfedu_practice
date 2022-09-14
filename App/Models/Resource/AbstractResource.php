<?php

namespace App\Models\Resource;

use App\Exception\Exception;
use App\Models\AbstractCarModel;
use App\Models\BrandModel;
use App\Models\LineModel;
use App\Models\Model;

abstract class AbstractResource
{
    protected function prepareValueSimpleMap(
        array $data,
        string $model = ''
    ): AbstractCarModel {
        $model = '\App\Models\\' . ucfirst($model) . 'Model';

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
        $model = '\App\Models\\' . ucfirst($model) . 'Model';

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

    protected function brandSelection(array $haystack): ?array
    {
        $haveBrand = (bool)$haystack['brandName'];

        if ($haveBrand) {
            $brand = new BrandModel();
            $brand
                ->setId($haystack['carBrandId'])
                ->setName($haystack['brandName'])
                ->setCountryName($haystack['countryName'])
                ->setCountryId($haystack['countryId'])
            ;

            unset($haystack['carBrandId']);
            unset($haystack['brandName']);
            unset($haystack['countryName']);
            unset($haystack['countryId']);

            return ['model' => $brand, 'data' => $haystack];
        }

        throw new Exception();
    }

    protected function lineSelection(array $haystack): ?array
    {
        $haveLine = (bool)$haystack['lineName'];

        if ($haveLine) {
            $line = new LineModel();
            $line
                ->setId($haystack['lineId'])
                ->setName($haystack['lineName'])
            ;

            unset($haystack['lineId']);
            unset($haystack['lineName']);

            return ['model' => $line, 'data' => $haystack];
        }

        throw new Exception();
    }

    protected function modelSelection(array $haystack): ?array
    {
        $haveModel = (bool)$haystack['modelName'];

        if ($haveModel) {
            $model = new Model();
            $model
                ->setId($haystack['modelId'])
                ->setName($haystack['modelName'])
            ;

            unset($haystack['modelId']);
            unset($haystack['modelName']);

            return ['model' => $model, 'data' => $haystack];
        }

        throw new Exception();
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
}
