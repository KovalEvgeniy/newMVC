<?php

class Route
{
    protected $controller;
    protected $conf;
    protected $params;

    public function __construct($conf)
    {
        $this->conf = $conf;
    }

    public function runUrl()
    {
        $parseUrl = $this->parseUrl();
        switch (count($parseUrl)) {
            case 0 :
                $this->setDefaultController();
                $this->runControllerAction($this->conf['default_action']);
                break;
            case 1 :
                $this->setApplicationController($parseUrl[0]);
                $this->runControllerAction($this->conf['default_action']);
                break;
            case 2 :
                $this->setApplicationController($parseUrl[0]);
                $this->runControllerAction($parseUrl[1]);
                break;
            case 3 :
                $this->setModulesController($parseUrl);
                $this->runControllerAction($parseUrl[2]);
                break;
            default:
                new \application\exceptions\Exception('Page Not Found!404');
                break;
        }
    }

    protected function runControllerAction($action)
    {
        $parameters = new \ReflectionMethod($this->controller, $action);
        $result = [];
        if (count($this->params) >= $parameters->getNumberOfParameters()) {
            foreach ($parameters->getParameters() as $parameter => $name) {
                foreach ($name as $key => $value) {
                    if (array_key_exists($value, $this->params)) {
                        $result[] = $this->params[$value];
                    }
                }
            }
            (new $this->controller())->$action(...$result);
        } else {
            new \application\exceptions\Exception('Need parameters for action!');
        }

    }

    protected function setDefaultController()
    {
        $this->controller = $this->conf['default_controller'];
    }

    protected function setApplicationController($url)
    {
        $this->controller = str_replace('{controller}', ucfirst($url), $this->conf['path_application']);
    }

    protected function setModulesController($url)
    {
        $this->controller = str_replace(['{module}', '{controller}'], [
            '{module}' => ucfirst($url[0]),
            '{controller}' => ucfirst($url[1])
        ], $this->conf['path_modules']);
    }

    protected function parseUrl()
    {
        $urls = parse_url($_SERVER['REQUEST_URI']);
        if (isset($urls['query'])) {
            $params = explode('&', $urls['query']);
            foreach ($params as $param) {
                $element = explode('=', $param);
                if ( !empty($element[0]) && !empty($element[1]) ) {
                    $this->params[$element[0]] = $element[1];
                }
            }
        }
        if ($urls['path'] === '/') {
            return [];
        }
        $url = explode('/', trim($urls['path'], '/'));

        return $url;
    }

}