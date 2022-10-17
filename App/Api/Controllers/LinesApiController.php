<?php

namespace App\Api\Controllers;

use App\Exception\ApiException;
use App\Exception\ResourceException;
use App\Exception\ServiceException;
use App\Models\Cache\Cache;
use App\Models\Cache\CacheStrategy;
use App\Models\Line;
use App\Models\Resource\LineResource;
use App\Models\Service\LineService;

class LinesApiController extends AbstractApiController
{
    protected const CACHE_KEY = 'line_info';

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

        $lineService = new LineService();
        $result = [];

        if ($this->getEntityIdParam()) {
            try {
                $this->renderJson($lineService->getInfo(
                    $this->getEntityIdParam()
                ));
            } catch (ServiceException $e) {
                throw new ApiException();
            }
        } else {
            $result = $lineService->getList();
        }

        $this->renderJson($result);
        $this->updateCache(self::CACHE_KEY, $lineService);

        return $result;
    }

    protected function postData()
    {
        $data = $this->validateRequiredData($this->getDataFromHttp(), [
            'name',
            'brand_id',
        ]);

        $line = new Line();
        $line
            ->setName($data['name'])
            ->setBrandId($data['brand_id']);

        $lineRecourse = new LineResource();

        try {
            $line = $lineRecourse->createEntity($line);

            $this->renderJson([
                'name'    => $line->getName(),
                'brand_id' => $line->getBrandId(),
            ]);
        } catch (ResourceException $e) {
            throw new ApiException();
        }

        return $this->updateCache(self::CACHE_KEY, new LineService());
    }

    protected function putData()
    {
        $this->checkEntityIdParam();
        $data = $this->validateRequiredData($this->getDataFromHttp(), [
            'modified_id',
            'name',
            'brand_id',
        ]);

        $line = new Line();
        $line
            ->setId($this->getEntityIdParam())
            ->setName($data['name'])
            ->setBrandId($data['brand_id'])
            ->setModifiedId($data['modified_id']);

        $lineRecourse = new LineResource();

        try {
            $lineRecourse->modifyAllProperties($line);
            $this->renderJson([
                'id'      => $data['modified_id'],
                'name'    => $data['name'],
                'brand_id' => $data['brand_id'],
            ]);
        } catch (ResourceException $e) {
            throw new ApiException();
        }

        return $this->updateCache(self::CACHE_KEY, new LineService());
    }

    protected function deleteData()
    {
        $this->checkEntityIdParam();
        $lineRecourse = new LineResource();

        if ($lineRecourse->delete($this->getEntityIdParam())) {
            return $this->updateCache(self::CACHE_KEY, new LineService());
        }

        throw new ApiException();
    }
}
