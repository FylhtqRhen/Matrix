<?php

namespace App;

use PDO;

class Connection
{
    private static $connect;

    private static $instance;

    private function __construct()
    {
        $bd = getenv('DB_CONNECTION');
        $dbName = getenv('DB_DATABASE');
        $name = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');
        $host = getenv('DB_HOST');
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        self::$connect = new PDO("$bd:host=$host;dbname=$dbName", $name, $password, $opt);
    }

    public static function get(): PDO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$connect;
    }
}