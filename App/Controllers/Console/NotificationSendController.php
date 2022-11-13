<?php

namespace App\Controllers\Console;

use App\Blocks\MailBlock;
use App\Controllers\ControllerInterface;
use App\Models\Service\MailCarService;
use App\Models\Resource\UserResource;
use App\Models\User;

class NotificationSendController implements ControllerInterface
{
    protected const MAIL_SUBJECT = 'Notification';
    protected $userEmail;

    public function execute()
    {
        $userResource = new UserResource();

        $user = new User();
        $user = $userResource->getByEmail(
            $user->setEmail($this->userEmail ?? null)
        );

        $mailService = new MailCarService();
        $mailService
            ->prepareMail($user)
            ->checkSendMail();
    }

    public function setArgument(string $userEmail): self
    {
        $this->userEmail = $userEmail;

        return $this;
    }
}
