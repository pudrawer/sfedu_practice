<?php

namespace App\CarLine\Controllers\Api;

use App\CarLine\Models\Line;
use App\CarLine\Models\Resource\LineResource;
use App\CarLine\Models\Service\LineCarService;
use App\Core\Controllers\Api\AbstractController;
use App\Core\Exception\ApiException;
use App\Core\Exception\ResourceException;
use App\Core\Exception\ServiceException;
use App\Core\Models\Cache\AbstractCache;
use Laminas\Di\Di;

class LinesController extends AbstractController
{
    protected static $cacheKey = 'line_info';

    public function __construct(
        Di $di,
        array $param,
        AbstractCache $cache,
        LineCarService $service,
        LineResource $resource
    ) {
        parent::__construct($di, $param, $cache, $service, $resource);
    }

    protected function getData()
    {
        if ($data = $this->checkCachedData()) {
            $this->renderJson($data);

            return $data;
        }

        $result = [];
        if ($this->getEntityIdParam()) {
            try {
                $this->renderJson($this->service->getInfo(
                    $this->getEntityIdParam()
                ));
            } catch (ServiceException $e) {
                throw new ApiException();
            }
        } else {
            $result = $this->service->getList();
        }

        $this->renderJson($result);
        $this->restoreCache($this->service);

        return $result;
    }

    protected function postData()
    {
        $data = $this->validateRequiredData($this->getDataFromHttp(), [
            'name',
            'brand_id',
        ]);

        $line = $this->di->get(Line::class);
        $line
            ->setName($data['name'])
            ->setBrandId($data['brand_id']);

        try {
            $line = $this->resource->createEntity($line);

            $this->renderJson([
                'name'    => $line->getName(),
                'brand_id' => $line->getBrandId(),
            ]);
        } catch (ResourceException $e) {
            throw new ApiException();
        }

        return $this->restoreCache($this->service);
    }

    protected function putData()
    {
        $this->checkEntityIdParam();
        $data = $this->validateRequiredData($this->getDataFromHttp(), [
            'modified_id',
            'name',
            'brand_id',
        ]);

        $line = $this->di->get(Line::class);
        $line
            ->setId($this->getEntityIdParam())
            ->setName($data['name'])
            ->setBrandId($data['brand_id'])
            ->setModifiedId($data['modified_id']);

        try {
            $this->resource->modifyAllProperties($line);
            $this->renderJson([
                'id'      => $data['modified_id'],
                'name'    => $data['name'],
                'brand_id' => $data['brand_id'],
            ]);
        } catch (ResourceException $e) {
            throw new ApiException();
        }

        return $this->restoreCache($this->service);
    }

    protected function deleteData()
    {
        $this->checkEntityIdParam();

        if ($this->resource->delete($this->getEntityIdParam())) {
            return $this->restoreCache($this->service);
        }

        throw new ApiException();
    }
}
