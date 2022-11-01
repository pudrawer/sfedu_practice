<?php

namespace App\Models\Mailer;

use App\Exception\Exception;
use App\Models\Environment\Environment;
use App\Models\User;
use GuzzleHttp;
use SendinBlue;

class Mailer
{
    private static $instance;

    private $mailer;
    private $smtp;

    private function __construct()
    {
        $config = SendinBlue\Client\Configuration::getDefaultConfiguration()
            ->setApiKey(
                'api-key',
                Environment::getInstance()->getSendinBlueApiKey()
            );

        $this->mailer = new SendinBlue\Client\Api\TransactionalEmailsApi(
            new GuzzleHttp\Client(),
            $config
        );
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function prepareMailProperties(
        User $user,
        string $subject
    ): self {
        $this->smtp = new \SendinBlue\Client\Model\SendSmtpEmail();

        $this->smtp['subject'] = $subject;
        $this->smtp['sender'] = [
            'name'  => Environment::getInstance()->getSendinBlueSenderName(),
            'email' => Environment::getInstance()->getSendinBlueSenderEmail(),
        ];
        $this->smtp['to'] = [
            ['email' => $user->getEmail(), 'name' => $user->getName()],
        ];

        return $this;
    }

    public function prepareMailBody(string $body): self
    {
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
