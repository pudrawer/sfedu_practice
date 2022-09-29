<?php

namespace App\Controllers;

use App\Exception\Exception;
use App\Models\Environment\Environment;
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
        string $neededModel,
        string $csrfToken
    ): bool {
        if ($csrfToken != Session::getInstance()->getCsrfToken()) {
            throw new Exception();
        }

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

    public function checkName(string $param): self
    {
        if (!preg_match('/^[a-zа-я]+$/ui', $param)) {
            throw new Exception('Bad name');
        }

        return $this;
    }

    public function checkYear(string $param): self
    {
        if (!preg_match('/[1-2][0-9]{3}/ui', $param)) {
            throw new Exception('Bad year');
        }

        return $this;
    }

    public function checkEmail(string $param): self
    {
        if (!preg_match('/^[\w\d_.+-]+@[\w\d-]+.[\w]+$/ui', $param)) {
            throw new Exception('Bad email');
        }

        return $this;
    }

    public function checkPhoneNumber(string $param): self
    {
        if (
            !preg_match(
                '/((8|\+7)-?)?\(?\d{3,5}\)?-?\d{1}-?\d{1}-?\d{1}-?\d{1}-?\d{1}((-?\d{1})?-?\d{1})?/',
                $param
            )
        ) {
            throw new Exception();
        }

        return $this;
    }
}
