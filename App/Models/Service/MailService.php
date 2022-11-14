<?php

namespace App\Models\Service;

use App\Blocks\MailBlock;
use App\Exception\Exception;
use App\Models\Mailer;
use App\Models\User;
use Laminas\Di\Di;

class MailService extends AbstractService
{
    protected $mailer;

    public function __construct(Di $di, Mailer $mailer)
    {
        parent::__construct($di);

        $this->mailer = $mailer;
    }

    public function prepareMail(User $user): self
    {
        $mailBlock = $this->di->get(MailBlock::class);
        $mailBlock->setUser($user);

        $this->mailer->prepareMailProperties(
            $user,
            'Hello!',
            $mailBlock->getTemplateHtml()
        );

        return $this;
    }

    public function checkSendMail()
    {
        if (!$this->mailer->sendMail()) {
            throw new Exception('Something was wrong' . PHP_EOL);
        }
    }
}
