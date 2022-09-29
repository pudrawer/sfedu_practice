<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\LineBlock;
use App\Models\Line;
use App\Models\Recourse\LineRecourse;
use App\Exception\Exception;
use App\Models\Session\Session;

class CarLineController extends AbstractController
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
            $lineResource = new LineRecourse();

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

        $this->checkName($this->getPostParam('name'));

        $this->changeProperties([
            'id',
            'name',
        ], 'line', Session::getInstance()->getCsrfToken());
        $this->redirectTo('carBrandList');
    }
}
