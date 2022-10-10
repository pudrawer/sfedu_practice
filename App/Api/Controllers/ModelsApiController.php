<?php

namespace App\Api\Controllers;

use App\Exception\ApiException;
use App\Exception\Exception;
use App\Exception\RecourseException;
use App\Models\Model;
use App\Models\Recourse\ModelRecourse;

class ModelsApiController extends AbstractApiController
{
    protected function getData()
    {
        $modelRecourse = new ModelRecourse();

        if ($this->param) {
            try {
                $model = $modelRecourse->getOnlyModelInfo($this->param);
                echo json_encode([
                    'id'         => $model->getId(),
                    'name'       => $model->getName(),
                    'lineId'     => $model->getLineId(),
                    'year'       => $model->getYear(),
                    'previousId' => $model->getPreviousId(),
                ]);
            } catch (RecourseException $e) {
                throw new ApiException();
            }

            return;
        }

        try {
            echo json_encode($modelRecourse->getOnlyModelsInformation());
        } catch (RecourseException $e) {
            throw new ApiException();
        }
    }

    protected function postData()
    {
        $data = $this->checkNeededData($this->getDataFromHttp(), [
            'name',
            'carLineId',
            'year',
            'previousModelId',
        ]);

        $model = new Model();
        $modelRecourse = new ModelRecourse();

        try {
            $modelRecourse->createEntity(
                $model
                    ->setName($data['name'])
                    ->setLineId($data['carLineId'])
                    ->setYear($data['year'])
                    ->setPreviousId($data['previousModelId'])
            );

            echo json_encode($data);
        } catch (RecourseException $e) {
            throw new ApiException();
        }
    }

    protected function putData()
    {
        $this->checkParam();
        $data = $this->checkNeededData($this->getDataFromHttp(), [
            'modifiedId',
            'name',
            'previousId',
            'year',
            'carLineId',
        ]);

        $model = new Model();
        $modelRecourse = new ModelRecourse();
        try {
            $modelRecourse->modifyAllInfo(
                $model
                    ->setId($this->param)
                    ->setModifiedId($data['modifiedId'])
                    ->setName($data['name'])
                    ->setPreviousId($data['previousId'])
                    ->setYear($data['year'])
                    ->setLineId($data['carLineId'])
            );
        } catch (RecourseException $e) {
            throw new ApiException();
        }

        echo json_encode([
            'id'         => $model->getModifiedId(),
            'name'       => $model->getName(),
            'previousId' => $model->getPreviousId(),
            'year'       => $model->getYear(),
            'carLineId'  => $model->getLineId(),
        ]);
    }

    protected function deleteData()
    {
        $this->checkParam();
        $modelRecourse = new ModelRecourse();
        if (!$modelRecourse->delete($this->param)) {
            throw new ApiException();
        }
    }
}
