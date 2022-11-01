<?php

namespace App\Controllers\Console;

use App\Blocks\MailBlock;
use App\Controllers\ControllerInterface;
use App\Models\Mailer\Mailer;
use App\Models\User;

class NotificationSendController implements ControllerInterface
{
    protected const MAIL_SUBJECT = 'Notification';
    protected $userEmail;

    public function execute()
    {
        $mailBlock = new MailBlock();
        $user = new User();
        $user
            ->setEmail($this->userEmail ?? null)
            ->setName('User');

        $mailBlock->setChildModels($user);

        Mailer::getInstance()
            ->prepareMailProperties($user, self::MAIL_SUBJECT)
            ->prepareMailBody($mailBlock->renderMail())
            ->sendMail();
    }

    public function setArgument(string $userEmail): self
    {
        $this->userEmail = $userEmail;

        return $this;
    }
}
