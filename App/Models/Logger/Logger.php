<?php

namespace App\Models\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

class Logger
{
    private static $instance = null;
    private $logger;

    private function __construct()
    {
        $this->logger = new MonologLogger('general');

        $this->logger->pushHandler(new StreamHandler(
            APP_ROOT . '/var/log/warning.log',
            MonologLogger::WARNING
        ));
        $this->logger->pushHandler(new StreamHandler(
            APP_ROOT . '/var/log/errors.log',
            MonologLogger::ERROR
        ));
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function putWarning(string $errstr)
    {
        $this->logger->warning($errstr);
    }

    public function putError(string $errstr)
    {
        $this->logger->error($errstr);
    }
}