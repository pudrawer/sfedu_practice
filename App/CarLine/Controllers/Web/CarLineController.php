<?php

namespace App\CarLine\Controllers\Web;

use App\CarLine\Blocks\LineBlock;
use App\CarLine\Models\Resource\LineResource;
use App\Core\Blocks\BlockInterface;
use App\Core\Controllers\Web\AbstractController;
use App\Core\Exception\Exception;
use App\Core\Models\Environment;
use App\Core\Models\Validator\Validator;
use Laminas\Di\Di;

class CarLineController extends AbstractController
{
    public function __construct(
        Di $di,
        Environment $env,
        LineResource $resource,
        LineBlock $block,
        Validator $validator,
        array $params = []
    ) {
        parent::__construct(
            $di,
            $env,
            $params,
            $resource,
            $block,
            $validator
        );
    }

    public function execute(): BlockInterface
    {
        if ($this->isGetMethod()) {
            $brandParam = $this->getParams['brand'] ?? null;
            $lineParam = $this->getParams['line'] ?? null;

            if (!$brandParam || !$lineParam) {
                throw new Exception('Bad get param' . PHP_EOL);
            }

            $data = $this->resource->getLineInfo($brandParam, $lineParam);
            $this->block
                ->setChildModels($data['brandModel'])
                ->setData($data['data'])
                ->setHeader([
                    $data['brandModel']->getName(),
                    $this->block->getData()->getName()
                ])
                ->render('carInfo');

            return $this->block;
        }

        $this->validator->checkName($this->getPostParam('name'));

        $this->changeProperties([
            'id',
            'name',
        ], 'line');
        $this->redirectTo('carBrandList');
    }
}
