<?php

namespace App\Helpers;

use App\Mail\ChangeEmailMail;
use Illuminate\Support\Facades\Mail;

class EmailChangeHelper
{
    /**
     * Send email change verification mail
     *
     * @param string $email The new email address
     * @param string $token The verification token
     * @param string $userType The user model type (user, visitor, exhibitor)
     * @param string $locale The locale to use for the email
     * @param string|null $name The name of the recipient
     */
    public static function sendVerificationEmail(string $email, string $token, string $userType, string $locale, ?string $name = null): void
    {
        Mail::to($email)->send(new ChangeEmailMail($token, $userType, $locale, $name));
    }
}
