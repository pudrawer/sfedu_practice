<?php

namespace App\Models\Service;

use App\Exception\ApiException;
use App\Exception\ResourceException;
use App\Exception\ServiceException;
use App\Models\Resource\BrandResource;

class BrandService extends AbstractService
{
    public function getList(): array
    {
        $recourse = new BrandResource();
        return $recourse->getInformation();
    }

    public function getInfo(int $id): array
    {
        $recourse = new BrandResource();
        $result = [];

        try {
            $brandInfo = $recourse->getBrandInfo($id);
        } catch (ResourceException $e) {
            throw new ServiceException();
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

        return $result;
    }
}
