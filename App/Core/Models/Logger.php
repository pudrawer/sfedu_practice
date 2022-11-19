<?php

namespace App\Core\Models;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

class Logger
{
    private $logger;

    public function __construct(
        MonologLogger $logger,
        StreamHandler $warningHandler,
        StreamHandler $errorHandler
    ) {
        $this->logger = $logger;

        $this->logger->pushHandler($warningHandler);
        $this->logger->pushHandler($errorHandler);
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
