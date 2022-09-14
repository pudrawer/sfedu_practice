<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\BrandBlock;
use App\Database\Database;
use App\Exception\Exception;
use App\Models\BrandModel;
use App\Models\Modification\BrandModification;
use App\Models\Resource\BrandRecourse;

class CarBrandController extends AbstractController
{
    public function execute(): BlockInterface
    {
        if (REQUEST_METHOD == 'GET') {
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
                ->render();
        }

        $this->changeProperties();
        $this->redirectTo('carBrandList');
    }

    public function changeProperties(): bool
    {
        $idParam   = htmlspecialchars($_POST['brandId']) ?? null;
        $nameParam = htmlspecialchars($_POST['brandName']) ?? null;
        $countryParam = htmlspecialchars($_POST['countryId']) ?? null;

        if (!$idParam || !$nameParam || !$countryParam) {
            throw new Exception('Bad post params' . PHP_EOL);
        }

        $model = new BrandModel();
        $model
            ->setId($idParam)
            ->setName($nameParam)
            ->setCountryId($countryParam);

        $modificator = new BrandModification();
        return $modificator->modifyProperties($model);
    }
}
