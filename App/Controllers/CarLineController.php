<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\LineBlock;
use App\Database\Database;

class CarLineController extends AbstractController
{
    public function execute(): BlockInterface
    {
        if (
            empty($this->getParam['brand'])
            ||
            empty($this->getParam['line'])
        ) {
            header('Location: http://localhost:8080/carBrandList');
            exit;
        }

        $block = new LineBlock();

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
        WHERE cb.id=? AND cl.id=?;
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
        $stmt->execute();
        $brandInfo = $stmt->fetch();

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
            $this->getParam['line'],
            \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT
        );
        $stmt->execute();
        $modelList = $stmt->fetchAll();


        return $block->setData([
            'commonInfo' => $brandInfo,
            'list' => $modelList,
        ])->render();
    }
}
