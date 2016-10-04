<?php

namespace core;

final class Config
{
    private static $_config = [];

    public static function get($key, $default = null)
    {
        if (!array_key_exists($key, self::$_config)) {
            return $default;
        }

        return self::$_config[$key] ?? $default;
    }

    public static function set($key, $value)
    {
        if ( !array_key_exists($value, self::$_config) ){
            self::$_config[$key] = $value;
        }
    }

    private function __construct() {}

    private function __clone() {}
    private function __wakeup() {}
    private function __sleep() {}
}