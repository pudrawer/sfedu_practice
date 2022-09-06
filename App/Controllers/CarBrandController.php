<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\BrandBlock;
use App\Database\Database;

class CarBrandController extends AbstractController
{
    public function execute(): BlockInterface
    {
        if (empty($this->getParam)) {
            header('Location: http://localhost:8080/carBrandList');
            exit;
        }

        $block = new BrandBlock();

        $connection = Database::getConnection();

        $stmt = $connection->prepare('
        SELECT 
            car_brand.*, 
            country.`name` as country_name 
        FROM car_brand 
            JOIN country 
                on car_brand.country_id = country.id 
        WHERE car_brand.id=:brand;
        ');
        $stmt->bindParam(
            1,
            $this->getParam['brand'],
            \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT
        );
        $stmt->execute();
        $brandInfo = $stmt->fetch();

        $stmt = $connection->prepare('
        SELECT id, name FROM car_line WHERE car_brand_id=:brand;
        ');
        $stmt->bindParam(
            1,
            $this->getParam['brand'],
            \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT
        );
        $stmt->execute();
        $lineList = $stmt->fetchAll();

        return $block->setData([
            'commonInfo' => $brandInfo,
            'list' => $lineList,
        ])->render();
    }
}
