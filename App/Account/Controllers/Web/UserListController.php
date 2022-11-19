<?php

namespace App\Account\Controllers\Web;

use App\Account\Blocks\UserListBlock;
use App\Account\Models\Resource\UserResource;
use App\Core\Controllers\Web\AbstractController;
use App\Core\Models\Environment;
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
