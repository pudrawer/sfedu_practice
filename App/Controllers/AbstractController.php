<?php

namespace App\Controllers;

use App\Exception\Exception;
use App\Models\Brand;
use App\Models\Recourse\BrandRecourse;

abstract class AbstractController implements ControllerInterface
{
    protected $getParams = [];
    protected const WEB_URI = 'http://localhost:8080';

    public function __construct(array $params = [])
    {
        $this->getParams = $params;
    }

    public function redirectTo(string $webPath = '')
    {
        header("Location: " . self::WEB_URI . "/$webPath");
        exit;
    }

    public function getPostParam(string $key)
    {
        return $_POST[$key] ?? null;
    }

    public function isGetMethod(): bool
    {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    public function changeProperties(
        array $params,
        string $neededModel
    ): bool {
        $hasRequiredData = true;
        $paramsValue = [];
        foreach ($params as $param) {
            $value = htmlspecialchars($this->getPostParam($param));

            $hasRequiredData = $hasRequiredData && $value;
            $paramsValue[$param] = $value;
        }

        if (!$hasRequiredData) {
            throw new Exception('Bad post params' . PHP_EOL);
        }

        $model = '\App\Models\\' . ucfirst($neededModel);
        $model = new $model();

        foreach ($paramsValue as $key => $param) {
            $method = 'set' . ucfirst($key);

            $model->$method($param);
        }

        $modificator = '\App\Models\Recourse\\' . ucfirst($neededModel) . 'Recourse';
        $modificator = new $modificator();
        return $modificator->modifyProperties($model);
    }
}
