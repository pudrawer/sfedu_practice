<?php

namespace App\Models\Session;

class Session
{
    private static $instance;

    public function __construct()
    {
        session_save_path(APP_ROOT . '/var/sessions');
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function start(): self
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return $this;
    }

    public function setUserId(int $id): self
    {
        $_SESSION['userId'] = $id;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $_SESSION['userId'] ?? null;
    }

    public function addError(string $errStr): self
    {
        $_SESSION['error'][] = $errStr;

        return $this;
    }

    public function getError(): ?array
    {
        return $this->unset('error') ?? null;
    }

    public function addMessage(string $messageStr): self
    {
        $_SESSION['message'][] = $messageStr;

        return $this;
    }

    public function getMessages(): ?array
    {
        return $this->unset('message') ?? null;
    }

    private function unset(string $sessionKey): ?array
    {
        $str = [];
        if (isset($_SESSION[$sessionKey])) {
            $str = $_SESSION[$sessionKey];
            unset($_SESSION[$sessionKey]);
        }

        return $str ?? null;
    }
}
