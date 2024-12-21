<?php

namespace App\Actions;

use App\Models\User;
use App\Utils\PasswordUtils;
use App\Notifications\PasswordRegenerated;

class UserActions
{
    public static function regeneratePassword(User $user)
    {
        $new_password = PasswordUtils::generatePassword();
        $user->password = bcrypt($new_password);
        $user->save();

        $user->notify(new PasswordRegenerated($new_password));
    }
}
