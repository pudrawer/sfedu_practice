<?php

namespace App\Models;

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

    public function getLineList(): array
    {
        return $this->lineList;
    }

    public function __toString()
    {
        return 'BrandModel';
    }
}
