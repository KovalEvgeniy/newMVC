<?php

namespace application\controllers;

use core\controllers\BaseController;

class JoraController extends BaseController
{
    public function index()
    {
       return $this->view('jora');
    }

    public function create($id, $name = 'Jora')
    {
        return $this->view('jora');
    }
}