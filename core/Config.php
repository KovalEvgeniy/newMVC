<?php

final class Config
{
    private static $_config = [];

    public static function get($key, $default = null)
    {
        if (!array_key_exists($key, self::$_config)) {
            return $default;
        }

        return self::$_config[$key];
    }

    public static function set($key, $value)
    {
        self::$_config[$key] = $value;
    }

    protected function __construct() {}

    private function __clone()
    {

    }
    private function __wakeup()
    {

    }
}