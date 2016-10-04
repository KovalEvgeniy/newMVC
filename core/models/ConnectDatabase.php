<?php

namespace core\models;

use application\exceptions\Exception;

class ConnectDatabase
{
    private static $_connection;

    private function __construct() {}

    public static function getConnection()
    {
        if (self::$_connection !== null) {
            return self::$_connection;
        }

        $db = include \core\Config::get('root_path') . '/configs/db.php';

        try {
            self::$_connection = new \PDO(
                "mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['username'], $db['password']
            );
        } catch (\PDOException $e) {
           new Exception($e->getMessage());
        }
        return self::$_connection;
    }

    private function __clone() {}
    private function __wakeup() {}
    private function __sleep() {}
}