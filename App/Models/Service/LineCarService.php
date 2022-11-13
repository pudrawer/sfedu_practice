<?php

namespace App\Models\Service;

use App\Exception\ResourceException;
use App\Exception\ServiceException;
use App\Models\Resource\LineResource;

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
