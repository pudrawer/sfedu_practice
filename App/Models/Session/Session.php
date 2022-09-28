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

    public function getError(): array
    {
        $errors = $_SESSION['error'] ?: [];
        $this->unset('error');

        return $errors;
    }

    public function addMessage(string $messageStr): self
    {
        $_SESSION['message'][] = $messageStr;

        return $this;
    }

    public function getMessages(): array
    {
        $messages = $_SESSION['message'] ?: [];
        $this->unset('message');

        return $messages;
    }

    private function unset(string $sessionKey): self
    {
        if (isset($_SESSION[$sessionKey])) {
            unset($_SESSION[$sessionKey]);
        }

        return $this;
    }
}
