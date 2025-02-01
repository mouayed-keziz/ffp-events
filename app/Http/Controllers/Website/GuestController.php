<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public static function Home()
    {
        return view('website.pages.home');
    }

    public static function Event()
    {
        return view('website.pages.event');
    }

    public static function Articles()
    {
        return view('website.pages.articles');
    }

    public static function SignIn()
    {
        return ["message" => "Sign In"];
    }
    public static function SignUp()
    {
        return ["message" => "Sign Up"];
    }
}
