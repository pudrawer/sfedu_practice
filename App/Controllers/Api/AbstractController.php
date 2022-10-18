<?php

namespace App\Controllers\Api;

use App\Controllers\ControllerInterface;
use App\Exception\ApiException;
use App\Exception\UserApiException;
use App\Models\Cache\Cache;
use App\Models\Cache\CacheFactory;
use App\Models\Service\AbstractService;

abstract class AbstractController implements ControllerInterface
{
    protected $param;

    public function __construct(array $param)
    {
        $this->param = $param;
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
        $cache = new Cache();
        $data = json_decode($cache->get(static::$cacheKey), true);

        return $data[$entityId] ?? false;
    }

    protected function restoreCache(AbstractService $service): bool
    {
        $cache = CacheFactory::getInstance();

        $cache->del(static::$cacheKey);
        $cache->set(static::$cacheKey, json_encode($service->getList()));

        return true;
    }

    protected function getDecodedData(): ?array
    {
        $cache = CacheFactory::getInstance();

        return json_decode($cache->get(static::$cacheKey) ?? [], true);
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
