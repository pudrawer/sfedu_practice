<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\LineBlock;
use App\Database\Database;

class CarLineController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $brandParam = $this->getParams['brand'] ?? null;
        $lineParam  = $this->getParams['line'] ?? null;

        if (!$brandParam || !$lineParam) {
            $this->redirectTo('carBrandList');
        }

        $block = new LineBlock();

        $brandInfo = $this->prepareKeyMap(
            $this->getLineInfo(
                $brandParam,
                $lineParam
            )
        );
        return $block
            ->setHeader([
                'brandName' => $brandInfo['brandName'],
                'lineName' => $brandInfo['name'],
            ])
            ->setData([
                'countryName' => $brandInfo['countryName'],
                'list' => $this->getLineModels($lineParam),
                'brandId' => $brandInfo['brandId'],
                'lineId' => $brandInfo['id'],
            ])->render();
    }

    private function getLineInfo(
        int $brandId,
        int $lineId
    ): ?array {
        $connection = Database::getConnection();

        $stmt = $connection->prepare('
        SELECT
            cl.*,
            cb.`name` as brand_name,
            cb.`id` as brand_id,
            country.`name` as country_name
        FROM car_line cl
            JOIN car_brand cb
                on cl.car_brand_id = cb.id
            JOIN country
                on cb.country_id = country.id
        WHERE cb.id = :brand_id AND cl.id = :line_id LIMIT 1;
        ');

        $idParamMap = [
            ':brand_id' => $brandId,
            ':line_id' => $lineId,
        ];
        foreach ($idParamMap as $alias => &$value) {
            $stmt->bindParam(
                $alias,
                $value,
                \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT
            );
        }

        $stmt->execute();

        return $stmt->fetch();
    }

    private function getLineModels(
        int $lineId
    ): ?array {
        $connection = Database::getConnection();

        $stmt = $connection->prepare('
        SELECT
            car_model.id,
            car_model.`name`
        FROM car_model
        JOIN car_line cl 
            on car_model.car_line_id = cl.id
        WHERE car_model.car_line_id=?;
        ');
        $stmt->bindParam(
            1,
            $lineId,
            \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT
        );
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
