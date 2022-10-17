<?php

namespace App\Models\Service;

use App\Exception\ResourceException;
use App\Exception\ServiceException;
use App\Models\Resource\LineResource;

class LineService extends AbstractService
{
    public function getList(): array
    {
        $lineRecourse = new LineResource();

        return $lineRecourse->getInformation();
    }

    public function getInfo(int $id): array
    {
        $lineRecourse = new LineResource();
        try {
            $result = $lineRecourse->getById($id);
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
