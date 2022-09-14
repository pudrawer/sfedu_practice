<?php

namespace App\Database;

class Database
{
    private static $instance;

    public static function getInstance(): \PDO
    {
        if (self::$instance) {
            return self::$instance;
        }

        $host = 'localhost';
        $db   = 'Cars';
        $user = 'sasha';
        $pass = '123456';
        $charset = 'utf8';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        self::$instance = new \PDO($dsn, $user, $pass, $opt);
        return self::$instance;
    }
}
