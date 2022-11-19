<?php

namespace App\Account\Controllers\Web;

use App\Account\Blocks\UserCarsBlock;
use App\Account\Models\User;
use App\Core\Blocks\BlockInterface;
use App\Core\Controllers\Web\AbstractController;
use App\Core\Models\Environment;
use App\Core\Models\Session\Session;
use App\SpecificCar\Models\Resource\CarResource;
use Laminas\Di\Di;

class UserCarsController extends AbstractController
{
    public function __construct(
        Di $di,
        Environment $env,
        UserCarsBlock $block,
        CarResource $resource,
        Session $session,
        array $params = []
    ) {
        parent::__construct(
            $di,
            $env,
            $params,
            $resource,
            $block,
            null,
            $session
        );
    }

    public function execute(): BlockInterface
    {
        $idParam = $this->session->start()->getUserId();

        if (!$idParam) {
            $this->redirectTo('login');
        }

        $user = $this->di->get(User::class);

        $modelList = $this->resource->getUserCarList($user);
        foreach ($modelList as $model) {
            $this->block->setChildCar($model);
        }

        return $this->block
            ->setHeader(['MY CARS'])
            ->render('specificCar');
    }
}
