<?php

namespace App\Controllers;

use App\Blocks\BlockInterface;
use App\Blocks\UserCarsBlock;
use App\Models\Recourse\CarRecourse;
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
        $carResource = new CarRecourse();
        $user = new User($idParam);

        $modelList = $carResource->getUserCarList($user);
        foreach ($modelList as $model) {
            $block->setChildModelsList($model);
        }

        return $block
            ->setHeader(['page' => 'MY CARS'])
            ->render($block->getActiveLink());
    }
}
