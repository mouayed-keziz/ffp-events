<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public static function LogIn()
    {
        return view('website.pages.auth.login');
    }
}
