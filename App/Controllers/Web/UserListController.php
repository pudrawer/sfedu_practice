<?php

namespace App\Controllers\Web;

use App\Blocks\UserListBlock;
use App\Models\Resource\UserResource;

class UserListController extends AbstractController
{
    public function execute()
    {
        $userResource = new UserResource();

        $userListBlock = new UserListBlock();
        $userListBlock->setData($userResource->getUserList());

        $userListBlock->render('main');
    }
}
