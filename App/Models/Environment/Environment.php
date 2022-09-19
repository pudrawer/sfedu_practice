<?php

namespace App\Models\Environment;

class Environment
{
    private static $instance;

    private $dbHost;
    private $dbName;
    private $dbUser;
    private $dbPass;
    private $dbChar;

    private $host;

    public function __construct(string $envPath)
    {
        $data = parse_ini_file($envPath, true);

        $this->dbHost = $data['DB']['HOST'];
        $this->dbName = $data['DB']['DATABASE'];
        $this->dbUser = $data['DB']['USER'];
        $this->dbPass = $data['DB']['PASS'];
        $this->dbChar = $data['DB']['CHARSET'];

        $this->host = $data['HOST']['ADDRESS'];
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self(APP_ROOT . '/.env');
        }

        return self::$instance;
    }

    public function getDbHost(): string
    {
        return $this->dbHost ?? '';
    }

    public function getDbName(): string
    {
        return $this->dbName ?? '';
    }

    public function getDbUser(): string
    {
        return $this->dbUser ?? '';
    }

    public function getDbPass(): string
    {
        return $this->dbPass ?? '';
    }

    public function getDbChar(): string
    {
        return $this->dbChar ?? '';
    }

    public function getHost(): string
    {
        return $this->host ?? '';
    }
}
