<?php

namespace core\controllers;

use core\Views;

class BaseController
{
    protected function view($view, $params = [])
    {
        return (new Views())->view($view, $params, get_class($this));
    }
}