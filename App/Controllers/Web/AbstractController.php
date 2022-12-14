<?php

namespace App\Controllers\Web;

use App\Blocks\AbstractBlock;
use App\Exception\Exception;
use App\Exception\ForbiddenException;
use App\Models\Environment;
use App\Models\Randomizer\Randomizer;
use App\Models\Resource\AbstractResource;
use App\Models\Service\AbstractService;
use App\Models\Session\Session;
use App\Models\Validator\Validator;
use Laminas\Di\Di;

abstract class AbstractController extends \App\Controllers\AbstractController
{
    protected $getParams;
    protected $block;
    protected $resource;
    protected $validator;
    protected $session;
    protected $randomizer;
    protected $service;
    protected $env;

    public function __construct(
        Di $di,
        Environment $env,
        array $params = [],
        AbstractResource $resource = null,
        AbstractBlock $block = null,
        Validator $validator = null,
        Session $session = null,
        Randomizer $randomizer = null,
        AbstractService $service = null
    ) {
        parent::__construct($di);

        $this->getParams  = $params;

        $this->block      = $block;
        $this->resource   = $resource;
        $this->validator  = $validator;
        $this->session    = $session;
        $this->randomizer = $randomizer;
        $this->service    = $service;
        $this->env = $env;
    }

    public function redirectTo(string $webPath = '')
    {
        $host = $this->env->getHost();
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

        $model = 'App\Models\\' . ucfirst($neededModel);
        $model = $this->di->get($model);

        foreach ($paramsValue as $key => $param) {
            $method = 'set' . ucfirst($key);

            $model->$method($param);
        }

        $modificator = 'App\Models\Recourse\\' . ucfirst($neededModel) . 'Recourse';
        $modificator = $this->di->get($modificator);
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
            != $this->di->get(Session::class)->getCsrfToken()
        ) {
            throw new ForbiddenException();
        }

        return true;
    }
}
