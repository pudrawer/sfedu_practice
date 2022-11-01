<?php

namespace App\Controllers\Web;

use App\Exception\Exception;
use App\Exception\ResourceException;
use App\Models\Resource\UserResource;
use App\Models\Service\MailService;
use App\Models\User;

class MailController extends AbstractController
{
    public function execute()
    {
        $userId = $this->getId();

        if (!$userId) {
            throw new Exception('Bad get param' . PHP_EOL);
        }

        $userResource = new UserResource();

        $user = new User();
        try {
            $user = $userResource->getInfo($user->setId($userId));
        } catch (ResourceException $e) {
            throw new Exception($e->getMessage() . PHP_EOL);
        }

        $mailService = new MailService();
        $mailService
            ->prepareMail($user)
            ->checkSendMail();

        $this->redirectTo('userList');
    }
}
