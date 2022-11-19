<?php

namespace App\Core\Database;

use App\Core\Models\Environment;

class Database
{
    protected $env;

    public function __construct(Environment $env)
    {
        $this->env = $env;
    }

    public function getPdo(): \PDO
    {
        $host    = $this->env->getDbHost();
        $db      = $this->env->getDbName();
        $user    = $this->env->getDbUser();
        $pass    = $this->env->getDbPass();
        $charset = $this->env->getDbChar();

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];

        return new \PDO($dsn, $user, $pass, $opt);
    }
}
