<?php

namespace App\Core\Controllers\Api;

use App\Core\Exception\ApiException;
use App\Core\Exception\UserApiException;
use App\Core\Models\Cache\AbstractCache;
use App\Core\Models\Resource\AbstractResource;
use App\Core\Models\Service\AbstractService;
use Laminas\Di\Di;

abstract class AbstractController extends \App\Core\Controllers\AbstractController
{
    protected $param;
    protected $resource;
    protected $service;
    protected $cache;

    public function __construct(
        Di $di,
        array $param,
        AbstractCache $cache,
        ?AbstractService $service,
        AbstractResource $resource
    ) {
        parent::__construct($di);

        $this->param = $param;

        $this->cache    = $cache;
        $this->service  = $service;
        $this->resource = $resource;
    }

    abstract protected function getData();

    abstract protected function postData();

    abstract protected function putData();

    abstract protected function deleteData();

    public function execute()
    {
        $method = $this->getRequestMethod() . 'Data';

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        throw new ApiException('Bad method');
    }

    public function getRequestMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD'] ?? 'get');
    }

    protected function getDataFromHttp(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

    protected function validateRequiredData(
        array $data,
        array $neededParams
    ): array {
        foreach ($neededParams as $param) {
            if (!isset($data[$param])) {
                throw new UserApiException('Bad request param' . PHP_EOL);
            }
        }

        return $data;
    }

    protected function checkEntityIdParam()
    {
        if (!$this->getEntityIdParam()) {
            throw new UserApiException('Bad request param' . PHP_EOL);
        }
    }

    protected function renderJson(array $data)
    {
        header('Content-Type: application/json');

        echo json_encode($data);
    }

    protected function getEntityIdParam(): ?int
    {
        return $this->param[0] ?? null;
    }

    /**
     * @param int $entityId
     * @return array|false
     */
    protected function getEntityFromCache(int $entityId): array
    {
        $data = json_decode($this->cache->get(static::$cacheKey), true);

        return $data[$entityId] ?? false;
    }

    protected function restoreCache(AbstractService $service): bool
    {
        $this->cache->del(static::$cacheKey);
        $this->cache->set(static::$cacheKey, json_encode($service->getList()));

        return true;
    }

    protected function getDecodedData(): ?array
    {
        return json_decode($this->cache->get(static::$cacheKey) ?? [], true);
    }

    protected function checkCachedData()
    {
        if ($id = $this->getEntityIdParam()) {
            $data = $this->getEntityFromCache($id);

            if ($data) {
                return $data;
            }
        } else {
            if ($data = $this->getDecodedData()) {
                return $data;
            }
        }

        return null;
    }
}
