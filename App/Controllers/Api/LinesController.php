<?php

namespace App\Controllers\Api;

use App\Exception\ApiException;
use App\Exception\ResourceException;
use App\Exception\ServiceException;
use App\Models\Cache\CacheFactory;
use App\Models\Line;
use App\Models\Resource\LineResource;
use App\Models\Service\LineService;

class LinesController extends AbstractController
{
    protected static $cacheKey = 'line_info';

    protected function getData()
    {
        $cache = CacheFactory::getInstance();

        if ($data = $this->checkCachedData()) {
            $this->renderJson($data);

            return $data;
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
        $this->restoreCache($lineService);

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

        return $this->restoreCache(new LineService());
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

        return $this->restoreCache(new LineService());
    }

    protected function deleteData()
    {
        $this->checkEntityIdParam();
        $lineRecourse = new LineResource();

        if ($lineRecourse->delete($this->getEntityIdParam())) {
            return $this->restoreCache(new LineService());
        }

        throw new ApiException();
    }
}
