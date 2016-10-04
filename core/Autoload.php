<?php

class Autoload
{
    private $className;

    function __construct($className)
    {
        $this->className = $className;
    }

    public function load()
    {
        $classPath = __DIR__ . '/../' .
            str_replace("\\", "/", $this->className) . '.php';

        if (file_exists($classPath)) {
            include $classPath;
        } else {
            new \application\exceptions\Exception('Class not exist!');
        }
    }
}