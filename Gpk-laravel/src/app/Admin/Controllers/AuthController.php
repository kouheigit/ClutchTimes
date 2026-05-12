<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AuthController as BaseAuthController;

class AuthController extends BaseAuthController
{
    /**
     * ログインページのタイトル
     *
     * @var string
     */
    protected $loginTitle = '空ノ庭 管理画面';

    /**
     * ログイン後のリダイレクト先
     *
     * @var string
     */
    protected $redirectTo = '/admin';
}

