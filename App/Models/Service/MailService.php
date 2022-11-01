<?php

namespace App\Models\Service;

use App\Blocks\MailBlock;
use App\Exception\Exception;
use App\Models\Mailer;
use App\Models\User;

class MailService
{
    public function prepareMail(User $user): self
    {
        $mailBlock = new MailBlock();
        $mailBlock->setUser($user);

        Mailer::getInstance()->prepareMailProperties(
            $user,
            'Hello!',
            $mailBlock->renderMail()
        );

        return $this;
    }

    public function checkSendMail()
    {
        if (!Mailer::getInstance()->sendMail()) {
            throw new Exception('Something was wrong' . PHP_EOL);
        }
    }
}
