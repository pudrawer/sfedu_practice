<?php

namespace App\Api\Controllers;

use App\Exception\ApiException;
use App\Exception\ResourceException;
use App\Models\Brand;
use App\Models\Resource\BrandRecourse;

class BrandsApiController extends AbstractApiController
{
    protected function getData()
    {
        $result = [];
        $recourse = new BrandRecourse();
        if ($this->getEntityIdParam()) {
            try {
                $brandInfo = $recourse->getBrandInfo($this->getEntityIdParam());
            } catch (ResourceException $e) {
                throw new ApiException();
            }

            $result['name'] = $brandInfo->getName();
            $result['country_name'] = $brandInfo->getCountryName();
            $result['country_id'] = $brandInfo->getCountryId();
            $result['line_list'] = [];

            foreach ($brandInfo->getLineList() as $line) {
                $temp['id'] = $line->getId();
                $temp['name'] = $line->getName();

                $result['line_list'][] = $temp;
            }

            $this->renderJson($result);
            return;
        }

        $brandList = $recourse->getAllInformation('car_brand');
        $temp = [];

        foreach ($brandList as $brand) {
            $temp['id'] = $brand->getId();
            $temp['name'] = $brand->getName();

            $result[] = $temp;
        }

        $this->renderJson($result);
    }

    protected function postData()
    {
        $data = $this->validateRequiredData($this->getDataFromHttp(), [
            'name',
            'county_id',
        ]);

        $brandModel = new Brand();
        $brandModel
            ->setName($data['name'])
            ->setCountryId($data['country_id']);

        $recourse = new BrandRecourse();
        if (!$recourse->createNewEntity($brandModel)) {
            throw new ApiException('Something was wrong' . PHP_EOL);
        }

        $this->renderJson([
            'name'      => $brandModel->getName(),
            'country_id' => $brandModel->getCountryId(),
        ]);
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

        $brandRecourse = new BrandRecourse();
        if ($brandRecourse->modifyAllProperties($brandModel)) {
            $this->renderJson([
                'id'        => $brandModel->getModifiedId(),
                'name'      => $brandModel->getName(),
                'country_id' => $brandModel->getCountryId(),
            ]);
            return;
        }

        throw new ApiException('Something was wrong' . PHP_EOL);
    }

    protected function deleteData()
    {
        $this->checkEntityIdParam();

        $brandRecourse = new BrandRecourse();
        $brandRecourse->delete($this->getEntityIdParam());
    }
}
