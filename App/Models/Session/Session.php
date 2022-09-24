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

    public function getUserId(): int
    {
        return $_SESSION['userId'];
    }

    public function setError(string $errStr): self
    {
        $_SESSION['error'] = $errStr;

        return $this;
    }

    public function getError(): string
    {
        return $this->unsetError() ?? '';
    }

    private function unsetError(): ?string
    {
        $error = '';
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            session_destroy();
        }

        return $error ?? null;
    }
}
