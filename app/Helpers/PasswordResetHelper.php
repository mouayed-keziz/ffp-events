<?php

namespace App\Helpers;

use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;

class PasswordResetHelper
{
    /**
     * Send a password reset email to the user
     * 
     * @param string $email User email address
     * @param string $token Reset token
     * @param mixed $userId User ID
     * @param string|null $locale Locale for the email (en, fr, ar)
     * @param string|null $name User's name
     * @return void
     */
    public static function sendResetEmail(string $email, string $token, $userId, ?string $locale = null, ?string $name = null)
    {
        Mail::to($email)->send(new ResetPasswordMail(
            token: $token,
            model: $userId,
            locale: $locale,
            name: $name
        ));
    }
}
