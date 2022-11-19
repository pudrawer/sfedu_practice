<?php

namespace App\CarLine\Models\Service;

use App\Car\Models\Service\AbstractCarService;
use App\Core\Exception\ResourceException;
use App\Core\Exception\ServiceException;

class LineCarService extends AbstractCarService
{
    public function getList(): array
    {
        return $this->resource->getInformation();
    }

    public function getInfo(int $id): array
    {
        try {
            $result = $this->resource->getById($id);
            return [
                'id'       => $result->getId(),
                'name'     => $result->getName(),
                'brand_id' => $result->getBrandId(),
            ];
        } catch (ResourceException $e) {
            throw new ServiceException();
        }
    }
}
