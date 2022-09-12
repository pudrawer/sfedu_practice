<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\BrandBlock;
use App\Database\Database;

class CarBrandController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $brandParam = $this->getParams['brand'] ?? null;

        if (!$brandParam) {
            $this->redirectTo('carBrandList');
        }

        $block = new BrandBlock();

        $brandInfo = $this->prepareKeyMap(
            $this->getBrandInfo($brandParam)
        );
        return $block
            ->setHeader([$brandInfo['name']])
            ->setData([
                'list' => $this->getCarLines($brandParam),
                'countryName' => $brandInfo['countryName'],
                'brandId' => $brandInfo['id'],
            ])
            ->render();
    }

    private function getBrandInfo(
        int $brandId
    ): ?array {
        $connection = Database::getConnection();

        $stmt = $connection->prepare('
        SELECT 
            car_brand.*, 
            country.`name` as country_name 
        FROM car_brand 
            JOIN country 
                on car_brand.country_id = country.id 
        WHERE car_brand.id=:brand_id LIMIT 1;
        ');
        $stmt->bindParam(
            ':brand_id',
            $brandId,
            \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT
        );
        $stmt->execute();

        return $stmt->fetch();
    }

    private function getCarLines(
        int $brandId
    ): ?array {
        $connection = Database::getConnection();

        $stmt = $connection->prepare('
        SELECT id, name FROM car_line WHERE car_brand_id = :brand_id;
        ');
        $stmt->bindParam(
            ':brand_id',
            $brandId,
            \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT
        );
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
