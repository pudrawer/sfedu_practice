<?php

namespace App\Controllers\Web;

use App\Blocks\BlockInterface;
use App\Blocks\RegistrationBlock;
use App\Blocks\UserCarsBlock;
use App\Models\Environment;
use App\Models\Randomizer\Randomizer;
use App\Models\Resource\CarResource;
use App\Models\Resource\RegistrationResource;
use App\Models\Resource\UserResource;
use App\Models\Session\Session;
use App\Models\User;
use App\Models\Validator\Validator;
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
