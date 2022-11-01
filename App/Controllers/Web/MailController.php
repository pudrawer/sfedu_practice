<?php

namespace App\Controllers\Web;

use App\Blocks\MailBlock;
use App\Exception\Exception;
use App\Exception\ResourceException;
use App\Models\Resource\UserResource;
use App\Models\User;
use App\Models\Mailer\Mailer;

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

        $mailBlock = new MailBlock();
        $mailBlock->setChildModels($user);

        $mail = Mailer::getInstance()
            ->prepareMailProperties($user, 'Hello!')
            ->prepareMailBody($mailBlock->renderMail());

        if (!$mail->sendMail()) {
            throw new Exception('Something was wrong' . PHP_EOL);
        }

        $this->redirectTo('userList');
    }
}
