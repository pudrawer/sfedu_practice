<?php

namespace App\Models\Environment;

use App\Models\Cache\CacheFactory;

class Environment
{
    private static $instance;
    private static $isCachedData = true;
    private static $data = [];

    private $dbHost;
    private $dbName;
    private $dbUser;
    private $dbPass;
    private $dbChar;

    private $host;

    private $cacheMode;

    private $sendinBlueApiKey;
    private $sendinBlueSenderEmail;
    private $sendinBlueSenderName;

    public function __construct(string $envPath)
    {
        if ($cache = CacheFactory::checkEnvCache()) {
            $cache = json_decode($cache, true);
            $this->setter($cache);

            return;
        }

        self::$data = parse_ini_file($envPath, true);
        $this->setter(self::$data);
        self::$isCachedData = false;
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self(APP_ROOT . '/.env');
        }

        if (!self::$isCachedData) {
            self::$isCachedData = true;
            CacheFactory::getInstance()->set('env', json_encode(self::$data));
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

    public function getCacheMode(): string
    {
        return $this->cacheMode ?? '';
    }

    public function getSendinBlueApiKey(): string
    {
        return $this->sendinBlueApiKey ?? '';
    }

    public function getSendinBlueSenderEmail(): string
    {
        return $this->sendinBlueSenderEmail ?? '';
    }

    public function getSendinBlueSenderName(): string
    {
        return $this->sendinBlueSenderName ?? '';
    }

    protected function setter(array $data)
    {
        $dbInfo = $data['DB'];
        $this->dbHost = $dbInfo['HOST'];
        $this->dbName = $dbInfo['DATABASE'];
        $this->dbUser = $dbInfo['USER'];
        $this->dbPass = $dbInfo['PASS'];
        $this->dbChar = $dbInfo['CHARSET'];

        $this->host = $data['HOST']['ADDRESS'];

        $this->cacheMode = $data['CACHE']['CACHE_MODE'];

        $sendinBlueInfo = $data['SENDIN_BLUE'];
        $this->sendinBlueApiKey      = $sendinBlueInfo['API_KEY'];
        $this->sendinBlueSenderEmail = $sendinBlueInfo['SENDER_EMAIL'];
        $this->sendinBlueSenderName  = $sendinBlueInfo['SENDER_NAME'];
    }
}
