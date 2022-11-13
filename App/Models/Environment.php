<?php

namespace App\Models;

use App\Models\Cache\AbstractCache;
use App\Models\Cache\FileCache;
use Predis\Client;

class Environment
{
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

    public function __construct(AbstractCache $cache)
    {
        if ($cachedData = $cache->get('env')) {
            $cachedData = json_decode($cachedData, true);
            $this->setProperties($cachedData);

            return;
        }

        $data = parse_ini_file(APP_ROOT . '/.env', true);
        $this->setProperties($data);
        $cache->set('env', json_encode($data));
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

    protected function setProperties(array $data)
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
