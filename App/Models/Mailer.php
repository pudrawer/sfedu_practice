<?php

namespace App\Models;

use App\Exception\Exception;
use GuzzleHttp;
use SendinBlue;

class Mailer
{
    private $mailer;
    private $smtp;
    private $env;

    public function __construct(Environment $env)
    {
        $this->env = $env;

        $config = SendinBlue\Client\Configuration::getDefaultConfiguration()
            ->setApiKey(
                'api-key',
                $this->env->getSendinBlueApiKey()
            );

        $this->mailer = new SendinBlue\Client\Api\TransactionalEmailsApi(
            new GuzzleHttp\Client(),
            $config
        );
    }

    public function prepareMailProperties(
        User $user,
        string $subject,
        string $body
    ): self {
        $this->smtp = new \SendinBlue\Client\Model\SendSmtpEmail();

        $this->smtp['subject'] = $subject;
        $this->smtp['sender'] = [
            'name'  => $this->env->getSendinBlueSenderName(),
            'email' => $this->env->getSendinBlueSenderEmail(),
        ];
        $this->smtp['to'] = [
            ['email' => $user->getEmail(), 'name' => $user->getName()],
        ];
        $this->smtp['htmlContent'] = $body;

        return $this;
    }

    public function sendMail(): bool
    {
        try {
            return (bool) $this->mailer->sendTransacEmail($this->smtp) ?? false;
        } catch (Exception $e) {
            return false;
        }
    }
}
