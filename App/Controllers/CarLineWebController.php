<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\LineBlock;
use App\Models\Line;
use App\Models\Resource\LineResource;
use App\Exception\Exception;
use App\Models\Session\Session;
use App\Models\Validator\Validator;

class CarLineWebController extends AbstractWebController
{
    public function execute(): BlockInterface
    {
        if ($this->isGetMethod()) {
            $brandParam = $this->getParams['brand'] ?? null;
            $lineParam = $this->getParams['line'] ?? null;

            if (!$brandParam || !$lineParam) {
                throw new Exception('Bad get param' . PHP_EOL);
            }

            $block = new LineBlock();
            $lineResource = new LineResource();

            $data = $lineResource->getLineInfo($brandParam, $lineParam);
            $block
                ->setChildModels($data['brandModel'])
                ->setData($data['data'])
                ->setHeader([
                    $data['brandModel']->getName(),
                    $block->getData()->getName()
                ])
                ->render('carInfo');

            return $block;
        }

        $validator = new Validator();
        $validator->checkName($this->getPostParam('name'));

        $this->changeProperties([
            'id',
            'name',
        ], 'line');
        $this->redirectTo('carBrandList');
    }
}
