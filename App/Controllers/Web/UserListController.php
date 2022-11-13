<?php

namespace App\Controllers\Web;

use App\Blocks\UserCarsBlock;
use App\Blocks\UserListBlock;
use App\Models\Environment;
use App\Models\Resource\CarResource;
use App\Models\Resource\UserResource;
use App\Models\Session\Session;
use Laminas\Di\Di;

class UserListController extends AbstractController
{
    public function __construct(
        Di $di,
        Environment $env,
        UserListBlock $block,
        UserResource $resource,
        array $params = []
    ) {
        parent::__construct(
            $di,
            $env,
            $params,
            $resource,
            $block
        );
    }

    public function execute()
    {
        $this->block->setData($this->resource->getUserList());

        $this->block->render('main');
    }
}
