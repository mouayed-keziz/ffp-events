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
    public static function Register()
    {
        return view('website.pages.auth.register');
    }
    public static function RestoreAccount()
    {
        return view('website.pages.auth.restore-account');
    }
    public static function EmailSent()
    {
        return view('website.pages.auth.email-sent');
    }
}
