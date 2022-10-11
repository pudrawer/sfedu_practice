<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\UserCarsBlock;
use App\Models\Resource\CarRecourse;
use App\Models\Session\Session;
use App\Models\User;

class UserCarsWebController extends AbstractWebController
{
    public function execute(): BlockInterface
    {
        $idParam = Session::getInstance()->start()->getUserId();

        if (!$idParam) {
            $this->redirectTo('login');
        }

        $block = new UserCarsBlock();
        $carResource = new CarRecourse();
        $user = new User($idParam);

        $modelList = $carResource->getUserCarList($user);
        foreach ($modelList as $model) {
            $block->setChildCar($model);
        }

        return $block
            ->setHeader(['MY CARS'])
            ->render('specificCar');
    }
}
