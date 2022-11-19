<?php

namespace App\Brand\Models\Service;

use App\Car\Models\Service\AbstractCarService;
use App\Core\Exception\ResourceException;
use App\Core\Exception\ServiceException;

class BrandCarService extends AbstractCarService
{
    public function getList(): array
    {
        return $this->resource->getInformation();
    }

    public function getInfo(int $id): array
    {
        $result = [];

        try {
            $brandInfo = $this->resource->getBrandInfo($id);
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
