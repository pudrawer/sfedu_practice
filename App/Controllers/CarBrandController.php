<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\BrandBlock;
use App\Database\Database;
use App\Exception\Exception;
use App\Models\BrandModel;
use App\Models\Resource\BrandRecourse;

class CarBrandController extends AbstractController
{
    public function execute(): BlockInterface
    {
        if ($this->isGetMethod()) {
            $brandParam = $this->getParams['brand'] ?? null;

            if (!$brandParam) {
                throw new Exception('Bad get param' . PHP_EOL);
            }

            $block = new BrandBlock();
            $model = new BrandRecourse();

            $brand = $model->getBrandInfo($brandParam);
            return $block
                ->setData($brand)
                ->setHeader([$brand->getName()])
                ->render($block->getActiveLink());
        }

        $this->changeProperties();
        $this->redirectTo('carBrandList');
    }

    public function changeProperties(): bool
    {
        $idParam      = htmlspecialchars($this->getPostParam('brandId'));
        $nameParam    = htmlspecialchars($this->getPostParam('brandName'));
        $countryParam = htmlspecialchars($this->getPostParam('countryId'));

        if (!$idParam || !$nameParam || !$countryParam) {
            throw new Exception('Bad post params' . PHP_EOL);
        }

        $model = new BrandModel();
        $model
            ->setId($idParam)
            ->setName($nameParam)
            ->setCountryId($countryParam);

        $modificator = new BrandRecourse();
        return $modificator->modifyProperties($model);
    }
}
