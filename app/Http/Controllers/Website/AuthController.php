<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $email = request()->query('email');
        $token = DB::table('password_reset_tokens')->where('email', $email)->first();
        if (!$token) {
            return redirect()->route('login');
        }
        return view('website.pages.auth.email-sent', ["email" => $email]);
    }
    public static function ResetPassword()
    {
        return view('website.pages.auth.reset-password');
    }
}
