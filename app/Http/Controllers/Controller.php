<?php

namespace App\Http\Controllers;

use App\Actions\UserActions;
use Illuminate\Http\Request;

abstract class Controller
{
    public function index(Request $request)
    {
        return "hello world";
    }
}
