<?php

namespace App\Brand\Models;

use App\CarLine\Models\Line;
use App\Core\Models\AbstractCarModel;

class Brand extends AbstractCarModel
{
    private $lineList = [];

    public function setLineList(array $lineList): self
    {
        foreach ($lineList as $value) {
            $model = new Line();
            $model
                ->setId($value['id'])
                ->setName($value['name']);

            $this->lineList[] = $model;
        }

        return $this;
    }

    /**
     * @return Line[]
     */
    public function getLineList(): array
    {
        return $this->lineList;
    }

    public function __toString()
    {
        return 'BrandModel';
    }
}
