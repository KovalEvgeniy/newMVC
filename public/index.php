<?php
ini_set('display_errors', 1);
require('../helpers/helpers.php');


require_multi(
    '../core/Config.php',
    '../core/Autoload.php',
    '../core/Route.php',
    '../core/models/ConnectDatabase.php'
    );

Config::set('time_start', microtime());
$config = require('../configs/router.php');
Config::set('root_path', __DIR__.'/..');

//Autoloader Classes
spl_autoload_register(function ($className) {
    (new Autoload($className))->load();
});

//Вызываем роуты
(new Route($config))->runUrl();

