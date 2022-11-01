<?php

namespace App\Controllers\Web;

use App\Controllers\ControllerInterface;
use App\Exception\Exception;
use App\Exception\ForbiddenException;
use App\Models\Environment;
use App\Models\Session\Session;

abstract class AbstractController implements ControllerInterface
{
    protected $getParams = [];

    public function __construct(array $params = [])
    {
        $this->getParams = $params;
    }

    public function redirectTo(string $webPath = '')
    {
        $webUri = Environment::getInstance();

        $host = $webUri->getHost();
        header("Location: $host/$webPath");
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
        $this->checkCsrfToken();

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

    public function getId(): ?string
    {
        return htmlspecialchars($this->getParams['id'] ?? '');
    }

    public function checkCsrfToken(): bool
    {
        if (
            $this->getPostParam('csrfToken')
            != Session::getInstance()->getCsrfToken()
        ) {
            throw new ForbiddenException();
        }

        return true;
    }
}
