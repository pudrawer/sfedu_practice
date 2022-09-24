<?php

namespace App\Models\Recourse;

use App\Database\Database;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Line;
use App\Models\Model;
use App\Models\User;

class CarRecourse extends AbstractRecourse
{
    /**
     * @param User $userModel
     * @return array
     */
    public function getUserCarList(User $userModel): ?array
    {
        $stmt = Database::getInstance()->prepare('
        SELECT
            cm.`id`   as model_id,
            cl.`id`   as line_id,
            cb.`id`   as brand_id,
        
            cm.`name` as model_name,
            cl.`name` as line_name,
            cb.`name` as brand_name,
        
            car.`id`,
            `num`,
            `vrc`,
            car.`year`
        FROM
            `car`
        JOIN
            car_model cm
                on car.car_model_id = cm.id
        JOIN
            car_line cl
                on cm.car_line_id = cl.id
        JOIN
            car_brand cb
                on cl.car_brand_id = cb.id
        WHERE `user_id` = :user_id;
        ');

        $stmt = $this->bindParamByMap($stmt, [
            ':user_id' => $userModel->getId(),
        ]);
        $stmt->execute();

        if (!$result = $stmt->fetchAll()) {
            return null;
        }


        foreach ($result as &$item) {
            $item = $this->prepareKeyMap($item);
        }

        return array_map([$this, 'split'], $result);
    }

    private function split(array $data): array
    {
        $brandModel = new Brand();
        $brandModel
            ->setId($data['brandId'])
            ->setName($data['brandName']);

        $lineModel = new Line();
        $lineModel
            ->setId($data['lineId'])
            ->setName($data['lineName']);

        $model = new Model();
        $model
            ->setId($data['modelId'])
            ->setName($data['modelName']);

        $car = new Car();
        $car
            ->setId($data['id'])
            ->setYear($data['year'])
            ->setNum($data['num'])
            ->setVrc($data['vrc']);

        return [
            'brand' => $brandModel,
            'line'  => $lineModel,
            'model' => $model,
            'car'   => $car,
        ];
    }
}
