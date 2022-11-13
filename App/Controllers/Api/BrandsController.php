<?php

namespace App\Controllers\Api;

use App\Exception\ApiException;
use App\Exception\ServiceException;
use App\Models\Brand;
use App\Models\Cache\AbstractCache;
use App\Models\Resource\BrandResource;
use App\Models\Service\BrandCarService;
use Laminas\Di\Di;

class BrandsController extends AbstractController
{
    protected static $cacheKey = 'brand_info';

    public function __construct(
        Di $di,
        array $param,
        AbstractCache $cache,
        BrandCarService $service,
        BrandResource $resource
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
        if ($id = $this->getEntityIdParam()) {
            try {
                $result = $this->service->getInfo($id);
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
            'country_id',
        ]);

        $brandModel = $this->di->get(Brand::class);
        $brandModel
            ->setName($data['name'])
            ->setCountryId($data['country_id']);

        if (!$this->resource->createNewEntity($brandModel)) {
            throw new ApiException('Something was wrong' . PHP_EOL);
        }

        $data = [
            'name'       => $brandModel->getName(),
            'country_id' => $brandModel->getCountryId(),
        ];
        $this->renderJson($data);

        $this->restoreCache($this->service);

        return $data;
    }

    protected function putData()
    {
        $this->checkEntityIdParam();
        $data = $this->validateRequiredData($this->getDataFromHttp(), [
            'id',
            'name',
            'country_id',
        ]);

        $brandModel = $this->di->get(Brand::class);
        $brandModel
            ->setId($this->getEntityIdParam())
            ->setName($data['name'])
            ->setCountryId($data['country_id'])
            ->setModifiedId($data['id']);

        if ($this->resource->modifyAllProperties($brandModel)) {
            $this->renderJson([
                'id'         => $brandModel->getModifiedId(),
                'name'       => $brandModel->getName(),
                'country_id' => $brandModel->getCountryId(),
            ]);

            $this->restoreCache($this->service);

            return $data;
        }

        throw new ApiException('Something was wrong' . PHP_EOL);
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
