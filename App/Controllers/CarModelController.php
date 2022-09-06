<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\ModelBlock;
use App\Database\Database;

class CarModelController extends AbstractController
{
    public function execute(): BlockInterface
    {
        if (
            empty($this->getParam['brand'])
            ||
            empty($this->getParam['line'])
            ||
            empty($this->getParam['model'])
        ) {
            header('Location: http://localhost:8080/carBrandList');
            exit;
        }

        $block = new ModelBlock();

        $connection = Database::getConnection();
        $stmt = $connection->prepare('
        SELECT
            cb.name as brand_name,
            cb.id as brand_id,
            cl.name as line_name,
            cl.id as line_id,
            cm.id,
            cm.name,
            cm.year,
            cm.previous_line_model as previous_id,
            country.name as country_name,
            (
                SELECT
                     `name`
                 FROM car_model
                 WHERE id = cm.previous_line_model
            ) as previous_name
            FROM car_model cm
            JOIN car_line cl
                ON cm.car_line_id = cl.id
            JOIN car_brand cb
                ON cl.car_brand_id = cb.id
            JOIN country
                ON country.id = cb.country_id
        WHERE car_brand_id = ? AND car_line_id = ? AND cm.id = ?;
        ');
        $stmt->bindParam(
            1,
            $this->getParam['brand'],
            \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT
        );
        $stmt->bindParam(
            2,
            $this->getParam['line'],
            \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT
        );
        $stmt->bindParam(
            3,
            $this->getParam['model'],
            \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT
        );
        $stmt->execute();
        $result = $stmt->fetch();

        $commonInfo = [
            'brand' => $result['brand_name'],
            'brandId' => $result['brand_id'],
            'line' => $result['line_name'],
            'lineId' => $result['line_id'],
            'model' => $result['name'],
            'modelId' => $result['id'],
            'country' => $result['country_name'],
        ];

        $modelInfo = [
            'year' => $result['year'],
            'previousId' => $result['previous_id'],
            'previousName' => $result['previous_name'],
        ];

        return $block->setData([
            'commonInfo' => $commonInfo,
            'modelInfo' => $modelInfo,

        ])->render();
    }
}
