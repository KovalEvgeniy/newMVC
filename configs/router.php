<?php

return [
    'default_controller' => '\application\controllers\HomeController',
    'default_action' => 'index',
    'path_application' => "\\application\\controllers\\{controller}Controller",
    'path_modules' => "\\modules\\{module}\\controllers\\{controller}Controller",
    'path_views' => "/views/{view}/{action}",
    'path_module_views' => "/modules/{module}/view/{view}/{action}",
];