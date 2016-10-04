<?php

namespace application\controllers;

use application\models\Test;
use core\controllers\BaseController;

class TestController extends BaseController
{
    public function index($from, $to)
    {
        $data = (new Test())
            ->select(['id', 'name', 'age', 'text', 'created_at', 'updated_at'])
            ->whereBetween('id', $from, $to)
            ->orderBy('id', 'desc')
            ->all();

        return $this->view('index', [
            'response' => $data,
        ]);
    }
}