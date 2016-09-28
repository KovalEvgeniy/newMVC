<?php

final class Config
{
    private static $_config = [];

    public static function get($key, $default = null)
    {
        if (!array_key_exists($key, self::$_config)) {
            return $default;
        }

        return self::$_config[$key];//@todo self::$_config[$key] ?? $default
    }

    public static function set($key, $value)
    {
        self::$_config[$key] = $value;//@todo проверка на существование
    }

    protected function __construct() {} //@todo private

    private function __clone()
    {

    }
    private function __wakeup()
    {

    }
    //@todo __sleep()
}