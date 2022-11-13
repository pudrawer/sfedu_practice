<?php

namespace App\Controllers\Web;

use App\Blocks\AbstractBlock;
use App\Blocks\BlockInterface;
use App\Blocks\LineBlock;
use App\Exception\Exception;
use App\Models\Environment;
use App\Models\Randomizer\Randomizer;
use App\Models\Resource\BrandResource;
use App\Models\Resource\LineResource;
use App\Models\Service\AbstractService;
use App\Models\Session\Session;
use App\Models\Validator\Validator;
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
