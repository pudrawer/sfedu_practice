<?php

namespace App\Api\Controllers;

use App\Exception\ApiException;
use App\Exception\RecourseException;
use App\Models\Brand;
use App\Models\Recourse\BrandRecourse;

class BrandsApiController extends AbstractApiController
{
    protected function getData()
    {
        $result = [];
        $recourse = new BrandRecourse();
        if ($this->param) {
            try {
                $brandInfo = $recourse->getBrandInfo($this->param);
            } catch (RecourseException $e) {
                throw new ApiException();
            }

            $result['name'] = $brandInfo->getName();
            $result['countryName'] = $brandInfo->getCountryName();
            $result['countryId'] = $brandInfo->getCountryId();
            $result['lineList'] = [];

            foreach ($brandInfo->getLineList() as $line) {
                $temp['id'] = $line->getId();
                $temp['name'] = $line->getName();

                $result['lineList'][] = $temp;
            }

            echo json_encode($result);
            return;
        }

        $brandList = $recourse->getBrandList();
        $temp = [];

        foreach ($brandList as $brand) {
            $temp['id'] = $brand->getId();
            $temp['name'] = $brand->getName();

            $result[] = $temp;
        }

        echo json_encode($result);
    }

    protected function postData()
    {
        $data = $this->checkNeededData($this->getDataFromHttp(), [
            'name',
            'countyId',
        ]);

        $brandModel = new Brand();
        $brandModel
            ->setName($data['name'])
            ->setCountryId($data['countryId']);

        $recourse = new BrandRecourse();
        if (!$recourse->createNewEntity($brandModel)) {
            throw new ApiException('Something was wrong' . PHP_EOL);
        }

        echo json_encode([
            'name'      => $brandModel->getName(),
            'countryId' => $brandModel->getCountryId(),
        ]);
    }

    protected function putData()
    {
        $this->checkParam();
        $data = $this->checkNeededData($this->getDataFromHttp(), [
            'id',
            'name',
            'countryId',
        ]);

        $brandModel = new Brand();
        $brandModel
            ->setId($this->param)
            ->setName($data['name'])
            ->setCountryId($data['countryId'])
            ->setModifiedId($data['id']);

        $brandRecourse = new BrandRecourse();
        if ($brandRecourse->modifyPropertiesByHttp($brandModel)) {
            echo json_encode([
                'id'        => $brandModel->getModifiedId(),
                'name'      => $brandModel->getName(),
                'countryId' => $brandModel->getCountryId(),
            ]);
        } else {
            throw new ApiException('Something was wrong' . PHP_EOL);
        }
    }

    protected function deleteData()
    {
        $this->checkParam();

        $brandRecourse = new BrandRecourse();
        $brandRecourse->delete($this->param);
    }
}
