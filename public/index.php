<?php
ini_set('display_errors', 1);
require('../core/Config.php');
require('../core/Autoload.php');
require('../helpers/helpers.php');
require('../core/Route.php');
require('../core/models/ConnectDatabase.php');

$config = require(__DIR__.'/../configs/router.php');

//Autoloader Classes
spl_autoload_register(function ($className) {
    (new Autoload($className))->load();
});
\core\Config::set('root_path', __DIR__ . '/..');

//Вызываем роуты
(new Route($config))->runUrl();
