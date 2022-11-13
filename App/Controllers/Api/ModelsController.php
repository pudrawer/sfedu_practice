<?php

namespace App\Controllers\Api;

use App\Exception\ApiException;
use App\Exception\ResourceException;
use App\Models\Cache\AbstractCache;
use App\Models\Model;
use App\Models\Resource\AbstractResource;
use App\Models\Resource\ModelResource;
use App\Models\Service\AbstractService;
use Laminas\Di\Di;

class ModelsController extends AbstractController
{
    public function __construct(
        Di $di,
        array $param,
        AbstractCache $cache,
        ModelResource $resource,
        AbstractService $service = null
    ) {
        parent::__construct($di, $param, $cache, $service, $resource);
    }

    protected function getData()
    {
        if ($this->getEntityIdParam()) {
            try {
                $model = $this->resource->getInfoById($this->getEntityIdParam());
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
            $this->renderJson($this->resource->getInformation());
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

        $model = $this->di->get(Model::class);

        try {
            $this->resource->createEntity(
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

        $model = $this->di->get(Model::class);
        try {
            $this->resource->modifyAllProperties(
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
        if (!$this->resource->delete($this->getEntityIdParam())) {
            throw new ApiException();
        }
    }
}
