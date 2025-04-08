<?php

namespace App\Activity;

use App\Enums\LogEvent;
use App\Enums\LogName;
use Illuminate\Database\Eloquent\Model;

class AuthenticationActivity
{
    /**
     * Log a user login event.
     *
     * @param Model|null $user
     * @return void
     */
    public static function logLogin(?Model $user): void
    {
        if ($user === null) {
            return;
        }

        activity()
            ->useLog(LogName::Authentication->value)
            ->event(LogEvent::Login->value)
            ->causedBy($user)
            ->withProperties([
                'email' => $user->email,
                'name' => $user->name,
            ])
            ->log('User logged in');
    }

    /**
     * Log a user logout event.
     *
     * @param Model|null $user
     * @return void
     */
    public static function logLogout(?Model $user): void
    {
        if ($user === null) {
            return;
        }

        activity()
            ->useLog(LogName::Authentication->value)
            ->event(LogEvent::Logout->value)
            ->causedBy($user)
            ->withProperties([
                'email' => $user->email,
                'name' => $user->name,
            ])
            ->log('User logged out');
    }
}
