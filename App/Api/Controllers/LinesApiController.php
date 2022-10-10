<?php

namespace App\Api\Controllers;

use App\Exception\ApiException;
use App\Exception\RecourseException;
use App\Models\Line;
use App\Models\Recourse\LineRecourse;

class LinesApiController extends AbstractApiController
{
    protected function getData()
    {
        $lineRecourse = new LineRecourse();

        if ($this->param) {
            $line = new Line();
            $line->setId($this->param);

            try {
                $result = $lineRecourse->getLineByHttp($line);
                echo json_encode([
                    'id'      => $result->getId(),
                    'name'    => $result->getName(),
                    'brandId' => $result->getBrandId(),
                ]);
            } catch (RecourseException $e) {
                throw new ApiException();
            }

            return;
        }

        echo json_encode($lineRecourse->getLinesByHttp());
    }

    protected function postData()
    {
        $data = $this->checkNeededData($this->getDataFromHttp(), [
            'name',
            'brandId',
        ]);

        $line = new Line();
        $line
            ->setName($data['name'])
            ->setBrandId($data['brandId']);

        $lineRecourse = new LineRecourse();

        try {
            $line = $lineRecourse->createEntity($line);

            echo json_encode([
                'name'    => $line->getName(),
                'brandId' => $line->getBrandId(),
            ]);
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
            'brandId',
        ]);

        $line = new Line();
        $line
            ->setId($this->param)
            ->setName($data['name'])
            ->setBrandId($data['brandId'])
            ->setModifiedId($data['modifiedId']);

        $lineRecourse = new LineRecourse();

        try {
            $lineRecourse->modifyPropertiesByHttp($line);
            echo json_encode([
                'id'      => $data['modifiedId'],
                'name'    => $data['name'],
                'brandId' => $data['brandId'],
            ]);
        } catch (RecourseException $e) {
            throw new ApiException();
        }
    }

    protected function deleteData()
    {
        $this->checkParam();
        $lineRecourse = new LineRecourse();

        if ($lineRecourse->delete($this->param)) {
            return;
        }

        throw new ApiException();
    }
}
