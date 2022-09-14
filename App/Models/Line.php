<?php

namespace App\Models;

class Line extends AbstractCarModel
{
    private $modelList = [];

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
}
