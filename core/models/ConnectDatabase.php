<?php

namespace core\models;

class ConnectDatabase
{
    private static $_connection;

    private function __construct() {}

    public static function getConnection()
    {
        if (self::$_connection !== null) {
            return self::$_connection;
        }

        $db = include \Config::get('root_path') . '/configs/db.php';

        try {//@todo должен быть общий обработчик ошибок
            self::$_connection = new \PDO(
                "mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['username'], $db['password']
            );
        } catch (\PDOException $e) {
            echo "<br>" . $e->getMessage();
        }
        return self::$_connection;
    }

    private function __clone() {}//@todo __wakeup() __sleep()
}