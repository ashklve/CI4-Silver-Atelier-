<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Users extends BaseController
{
    public function index(): string
    {
        return view('user/landing');
    }

    public function products(): string
    {
        return view('user/products');
    }

    public function login(): string
    {
        return view('user/login');
    }

    public function signup(): string
    {
        return view('user/signup');
    }
}