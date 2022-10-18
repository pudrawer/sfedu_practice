<?php

namespace App\Controllers\Api;

use App\Exception\ApiException;
use App\Exception\ServiceException;
use App\Models\Brand;
use App\Models\Cache\CacheFactory;
use App\Models\Resource\BrandResource;
use App\Models\Service\BrandService;

class BrandsController extends AbstractController
{
    protected static $cacheKey = 'brand_info';

    protected function getData()
    {
        $cache = CacheFactory::getInstance();

        if ($data = $this->checkCachedData()) {
            $this->renderJson($data);

            return $data;
        }

        $result = [];
        $brandService = new BrandService();
        if ($id = $this->getEntityIdParam()) {
            try {
                $result = $brandService->getInfo($id);
            } catch (ServiceException $e) {
                throw new ApiException();
            }
        } else {
            $result = $brandService->getList();
        }

        $this->renderJson($result);
        $this->restoreCache($brandService);

        return $result;
    }

    protected function postData()
    {
        $data = $this->validateRequiredData($this->getDataFromHttp(), [
            'name',
            'country_id',
        ]);

        $brandModel = new Brand();
        $brandModel
            ->setName($data['name'])
            ->setCountryId($data['country_id']);

        $recourse = new BrandResource();
        if (!$recourse->createNewEntity($brandModel)) {
            throw new ApiException('Something was wrong' . PHP_EOL);
        }

        $data = [
            'name'      => $brandModel->getName(),
            'country_id' => $brandModel->getCountryId(),
        ];
        $this->renderJson($data);

        $this->restoreCache(new BrandService());

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

        $brandModel = new Brand();
        $brandModel
            ->setId($this->getEntityIdParam())
            ->setName($data['name'])
            ->setCountryId($data['country_id'])
            ->setModifiedId($data['id']);

        $brandRecourse = new BrandResource();
        if ($brandRecourse->modifyAllProperties($brandModel)) {
            $this->renderJson([
                'id'         => $brandModel->getModifiedId(),
                'name'       => $brandModel->getName(),
                'country_id' => $brandModel->getCountryId(),
            ]);

            $this->restoreCache(new BrandService());

            return $data;
        }

        throw new ApiException('Something was wrong' . PHP_EOL);
    }

    protected function deleteData()
    {
        $this->checkEntityIdParam();

        $brandRecourse = new BrandResource();
        if ($brandRecourse->delete($this->getEntityIdParam())) {
            return $this->restoreCache(new BrandService());
        }

        throw new ApiException();
    }
}
