<?php

namespace App\Api\Controllers;

use App\Exception\ApiException;
use App\Exception\ServiceException;
use App\Models\Brand;
use App\Models\Cache\Cache;
use App\Models\Cache\CacheStrategy;
use App\Models\Resource\BrandResource;
use App\Models\Service\BrandService;

class BrandsApiController extends AbstractApiController
{
    protected const CACHE_KEY = 'brand_info';
    protected function getData()
    {
        $cache = CacheStrategy::chooseCache();

        if ($id = $this->getEntityIdParam()) {
            $data = $this->getEntityFromCache($id, self::CACHE_KEY);

            if ($data) {
                $this->renderJson($data);

                return $data;
            }
        } else {
            if ($data = json_decode($cache->get(self::CACHE_KEY), true)) {
                $this->renderJson($data);

                return $data;
            }
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
        $this->updateCache(self::CACHE_KEY, $brandService);

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

        $this->updateCache(self::CACHE_KEY, new BrandService());

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

            $this->updateCache(self::CACHE_KEY, new BrandService());

            return $data;
        }

        throw new ApiException('Something was wrong' . PHP_EOL);
    }

    protected function deleteData()
    {
        $this->checkEntityIdParam();

        $brandRecourse = new BrandResource();
        if ($brandRecourse->delete($this->getEntityIdParam())) {
            return $this->updateCache(self::CACHE_KEY, new BrandService());
        }

        throw new ApiException();
    }
}
