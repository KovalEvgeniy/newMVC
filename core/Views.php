<?php

namespace core;

use core\Config;

class Views
{
    protected $conf_views;
    protected $conf_route;

    public function view($view, $params, $controller)
    {
        $this->conf_route = require(Config::get('root_path').'/configs/router.php');
        $this->conf_views = require(Config::get('root_path').'/configs/router_views.php');
        $path = str_replace(['Controller', 'controllers\\'], '', stristr($controller, 'Controller'));

        foreach ($params as $key => $value) { //@todo extract()
            ${$key} = $params[$key];
        }

        if (stristr($controller, 'modules')) {
            if (stristr($view, '::')) {
                $pageAndAction = explode('::', $view);
                $view = $this->setModules($path, $pageAndAction);
            } else {
                $view = $this->setModules($path);
            }
        } else {
            if (stristr($view, '::')) {
                $pageAndAction = explode('::', $view);
                $view = $this->setApplication($path, $pageAndAction);
            } else {
                $view = $this->setApplication($path);
            }
        }
//        $viewPath = explode('::', $view);
//
//        if (stristr($controller, 'modules')) {
//            $view = $this->setModules($path, $viewPath[1] ?? $viewPath[0]);
//        } else {
//            $view = $this->setApplication($path, $viewPath[1] ?? $viewPath[0]);
//        }

        require(\core\Config::get('root_path') . $view . '.php');
    }

    protected function setModules($path, $url = [])
//    protected function setModules($path, $url)
    {
        return str_replace(['{module}', '{dir}','{action}'], [
            '{module}' => ucfirst($path),
            '{dir}' => strtolower($path),
            '{action}' => $url[0] ?? $this->conf_route['default_action'],
        ], $this->conf_views['path_module_views']);
//            '{action}' => $url[1] ,//?? $this->conf['default_action'],//@todo не должно быть дефолтного
    }

    protected function setApplication($path, $url = [])
//    protected function setApplication($path, $url)//@todo
    {
        return str_replace(['{dir}', '{action}'], [
            '{dir}' => strtolower($path),
            '{action}' => $url ?? $this->conf_route['default_action'],
        ], $this->conf_views['path_views']);
//            '{action}' => $url[1] ,//@todo не должно быть дефолтного
    }

}