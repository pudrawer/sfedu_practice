<?php

namespace App\Api\Controllers;

use App\Exception\ApiException;
use App\Exception\Exception;
use App\Exception\ResourceException;
use App\Models\Model;
use App\Models\Resource\ModelRecourse;

class ModelsApiController extends AbstractApiController
{
    protected function getData()
    {
        $modelRecourse = new ModelRecourse();

        if ($this->getEntityIdParam()) {
            try {
                $model = $modelRecourse->getOnlyModelInfo($this->getEntityIdParam());
                $this->renderJson([
                    'id'         => $model->getId(),
                    'name'       => $model->getName(),
                    'line_id'     => $model->getLineId(),
                    'year'       => $model->getYear(),
                    'previous_id' => $model->getPreviousId(),
                ]);
            } catch (ResourceException $e) {
                throw new ApiException();
            }

            return;
        }

        try {
            $this->renderJson($modelRecourse->getAllInformation('car_model'));
        } catch (ResourceException $e) {
            throw new ApiException();
        }
    }

    protected function postData()
    {
        $data = $this->validateRequiredData($this->getDataFromHttp(), [
            'name',
            'car_line_id',
            'year',
            'previous_model_id',
        ]);

        $model = new Model();
        $modelRecourse = new ModelRecourse();

        try {
            $modelRecourse->createEntity(
                $model
                    ->setName($data['name'])
                    ->setLineId($data['car_line_id'])
                    ->setYear($data['year'])
                    ->setPreviousId($data['previous_model_id'])
            );

            $this->renderJson($data);
        } catch (ResourceException $e) {
            throw new ApiException();
        }
    }

    protected function putData()
    {
        $this->checkEntityIdParam();
        $data = $this->validateRequiredData($this->getDataFromHttp(), [
            'modified_id',
            'name',
            'car_line_id',
            'year',
            'previous_model_id',
        ]);

        $model = new Model();
        $modelRecourse = new ModelRecourse();
        try {
            $modelRecourse->modifyAllProperties(
                $model
                    ->setId($this->getEntityIdParam())
                    ->setModifiedId($data['modified_id'])
                    ->setName($data['name'])
                    ->setPreviousId($data['previous_model_id'])
                    ->setYear($data['year'])
                    ->setLineId($data['car_line_id'])
            );
        } catch (ResourceException $e) {
            throw new ApiException();
        }

        $this->renderJson([
            'id'                => $model->getModifiedId(),
            'name'              => $model->getName(),
            'previous_model_id' => $model->getPreviousId(),
            'year'              => $model->getYear(),
            'car_line_id'       => $model->getLineId(),
        ]);
    }

    protected function deleteData()
    {
        $this->checkEntityIdParam();
        $modelRecourse = new ModelRecourse();
        if (!$modelRecourse->delete($this->getEntityIdParam())) {
            throw new ApiException();
        }
    }
}
