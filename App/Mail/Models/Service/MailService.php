<?php

namespace App\Mail\Models\Service;

use App\Account\Models\User;
use App\Core\Exception\Exception;
use App\Core\Models\Service\AbstractService;
use App\Mail\Blocks\MailBlock;
use App\Mail\Models\Mailer;
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
