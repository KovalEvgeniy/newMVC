<?php

namespace core;

class Views
{
    protected $conf;

    public function view($view, $params, $controller)
    {
        $this->conf = require('../configs/router.php');
        $path = str_replace(['Controller', 'controllers\\'], '', stristr($controller, 'Controller'));

        foreach ($params as $key => $value) {
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
        require(\Config::get('root_path') . $view . '.php');

    }

    protected function setModules($path, $url = [])
    {
        return $view = str_replace(['{module}', '{view}','{action}'], [
            '{module}' => ucfirst($path),
            '{view}' => strtolower($path),
            '{action}' => $url[1] ?? $this->conf['default_action'],
        ], $this->conf['path_module_views']);
    }

    protected function setApplication($path, $url = [])
    {
        return $view = str_replace(['{view}', '{action}'], [
            '{view}' => strtolower($path),
            '{action}' => $url[1] ?? $this->conf['default_action'],
        ], $this->conf['path_views']);
    }

}