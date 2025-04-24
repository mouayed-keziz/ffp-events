<?php

namespace App\Actions;

use App\Models\User;
use App\Utils\PasswordUtils;
use App\Notifications\PasswordRegenerated;
use Illuminate\Database\Eloquent\Model;

class UserActions
{
    public static function regeneratePassword(Model $user)
    {
        $new_password = PasswordUtils::generatePassword();
        $user->password = bcrypt($new_password);
        $user->save();
        $user->notify(new PasswordRegenerated($new_password));
    }
}
