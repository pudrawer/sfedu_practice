<?php

namespace App\Api\Controllers;

use App\Exception\ApiException;
use App\Exception\ResourceException;
use App\Models\Line;
use App\Models\Resource\LineResource;

class LinesApiController extends AbstractApiController
{
    protected function getData()
    {
        $lineRecourse = new LineResource();

        if ($this->getEntityIdParam()) {
            try {
                $result = $lineRecourse->getById($this->getEntityIdParam());
                $this->renderJson([
                    'id'      => $result->getId(),
                    'name'    => $result->getName(),
                    'brand_id' => $result->getBrandId(),
                ]);
            } catch (ResourceException $e) {
                throw new ApiException();
            }

            return;
        }

        $this->renderJson($lineRecourse->getInformation());
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
    }

    protected function deleteData()
    {
        $this->checkEntityIdParam();
        $lineRecourse = new LineResource();

        if (!$lineRecourse->delete($this->getEntityIdParam())) {
            throw new ApiException();
        }
    }
}
