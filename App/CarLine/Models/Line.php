<?php

namespace App\CarLine\Models;

use App\CarModel\Models\Model;
use App\Core\Models\AbstractCarModel;

class Line extends AbstractCarModel
{
    private $modelList = [];
    private $brandId = null;

    /**
     * @param Model[] $modelList
     * @return $this
     */
    public function setModelList(array $modelList): self
    {
        foreach ($modelList as $value) {
            $model = new Model();
            $model
                ->setId($value['id'])
                ->setName($value['name']);

            $this->modelList[] = $model;
        }

        return $this;
    }

    public function getModelList(): ?array
    {
        return $this->modelList;
    }

    public function __toString()
    {
        return 'LineModel';
    }

    public function setBrandId(int $brandId): self
    {
        $this->brandId = $brandId;

        return $this;
    }

    public function getBrandId(): ?int
    {
        return $this->brandId;
    }
}
