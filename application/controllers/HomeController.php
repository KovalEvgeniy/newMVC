<?php

namespace application\controllers;

use core\controllers\BaseController;

class HomeController extends BaseController
{
    public function index($id)
    {
        return $this->view('home::index');
    }

    public function create($id, $elem)
    {
        echo 'HomeController params = '.$id.','.$elem;
    }
}