<?php

namespace core;

class Views
{
    protected $conf;

    public function view($view, $params, $controller)
    {
        $this->conf = require('../configs/router.php');//@todo абсолютные пути у вьюх должен быть отдельное письмо
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

        require(\Config::get('root_path') . $view . '.php');
    }

    protected function setModules($path, $url = [])
//    protected function setModules($path, $url)//@todo
    {
        return $view = str_replace(['{module}', '{view}','{action}'], [//@todo зачем $view??
            '{module}' => ucfirst($path),
            '{view}' => strtolower($path),
            '{action}' => $url[1] ?? $this->conf['default_action'],//@todo не должно быть дефолтного
//            '{action}' => $url ?? $this->conf['default_action'],//@todo
        ], $this->conf['path_module_views']);
    }

    protected function setApplication($path, $url = [])
//    protected function setApplication($path, $url)//@todo
    {
        return $view = str_replace(['{view}', '{action}'], [//@todo зачем $view??
            '{view}' => strtolower($path),
            '{action}' => $url[1] ?? $this->conf['default_action'],//@todo не должно быть дефолтного
//            '{action}' => $url,//@todo
        ], $this->conf['path_views']);
    }

}