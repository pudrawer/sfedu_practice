<?php

namespace App\Mail\Controllers\Console;

use App\Account\Models\Resource\UserResource;
use App\Account\Models\User;
use App\Core\Controllers\ControllerInterface;
use App\Models\Service\MailCarService;

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
