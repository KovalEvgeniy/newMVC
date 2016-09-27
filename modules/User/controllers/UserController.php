<?php

namespace modules\User\controllers;

use core\controllers\BaseController;
use modules\User\models\User;

class UserController extends BaseController
{

    public function index()
    {
        $user = (new User)
//            ->where('id', 'between', 1, 3)
//            ->orderBy('id', 'desc')
//                ->where('aedf')
//            ->where(['id' => 1])
//            ->where(['id' => 2])
//            ->orWhere(['name' => 'QWERTY'])
//            ->where('id', 2)
            ->where('id', '>=', 2)
//            ->where(['firstname' => 'Jora'])
//            ->orWhere(['id' => 2])
//            ->whereBetween('id', 3, 5)
//            ->orWhereBetween('id', 3, 5) // пиздец
//                ->whereIdAndFirstNameAndLastname(2, 'Misha', "dvesfes")//
            ->all();
//        dd($user->getRequest()['where'], $user->getArguments());
        $this->view('user', [
            'title' => 'UserCtrl::index',
            'userAll' => $user,
        ]);
    }

    public function create()
    {
        echo 'UserController::create()';
    }
}