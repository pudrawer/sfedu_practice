<?php

namespace App\Controllers\Web;

use App\Blocks\AbstractBlock;
use App\Blocks\BlockInterface;
use App\Blocks\ModelBlock;
use App\Exception\Exception;
use App\Models\Environment;
use App\Models\Randomizer\Randomizer;
use App\Models\Resource\BrandResource;
use App\Models\Resource\ModelResource;
use App\Models\Service\AbstractService;
use App\Models\Session\Session;
use App\Models\Validator\Validator;
use Laminas\Di\Di;

class CarModelController extends AbstractController
{
    public function __construct(
        Di $di,
        Environment $env,
        ModelResource $resource,
        ModelBlock $block,
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
            $modelParam = $this->getParams['model'] ?? null;

            if (!$brandParam || !$lineParam || !$modelParam) {
                throw new Exception('Bad get param' . PHP_EOL);
            }

            $data = $this->resource->getModelInfo($brandParam, $lineParam, $modelParam);
            $this->block
                ->setHeader([
                    $data['brandModel']->getName(),
                    $data['lineModel']->getName(),
                    $data['data']->getName()
                ])
                ->setChildModels($data['brandModel'])
                ->setChildModels($data['lineModel'])
                ->setData($data['data'])
                ->render('carInfo');

            return $this->block;
        }

        $this->validator
            ->checkName($this->getPostParam('name'))
            ->checkYear($this->getPostParam('year'));

        $this->changeProperties([
            'id',
            'name',
            'year',
            'previousId',
        ], 'model');
        $this->redirectTo('carBrandList');
    }
}
