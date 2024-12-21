<?php

namespace App\Utils;

use Illuminate\Support\Str;

class PasswordUtils
{
    public static function generatePassword()
    {
        return Str::random(12);


        //  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        // $password = substr(str_shuffle($chars), 0, 12);
        // return $password;
    }
}
