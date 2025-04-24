<?php

namespace App\Actions;

use App\Models\User;
use App\Utils\PasswordUtils;
use App\Notifications\PasswordRegenerated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class UserActions
{
    public static function regeneratePassword(Model $user)
    {
        Log::info('Regenerating password for user: ' . $user->email);
        $new_password = PasswordUtils::generatePassword();
        $user->password = bcrypt($new_password);
        $user->save();
        Log::info('New password generated for user: ' . $user->email . ' - ' . $new_password);
        $user->notify(new PasswordRegenerated($new_password));
        Log::info('Password regeneration notification sent to user: ' . $user->email);
    }
}
