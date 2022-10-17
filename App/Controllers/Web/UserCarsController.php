<?php

namespace App\Controllers\Web;

use App\Blocks\BlockInterface;
use App\Blocks\UserCarsBlock;
use App\Models\Resource\CarResource;
use App\Models\Session\Session;
use App\Models\User;

class UserCarsController extends AbstractController
{
    public function execute(): BlockInterface
    {
        $idParam = Session::getInstance()->start()->getUserId();

        if (!$idParam) {
            $this->redirectTo('login');
        }

        $block = new UserCarsBlock();
        $carResource = new CarResource();
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
