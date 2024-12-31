<?php

namespace App\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use App\Models\Log;
use App\Models\User;
use Filament\Notifications\Notification;

class LogActions
{
    public static function deleteAllLogs(string $password): void
    {
        if (Hash::check($password, Auth::user()->password)) {
            Log::truncate();
            Notification::make()
                ->success()
                ->title(__('panel/logs.actions.delete_all.notifications.success.title'))
                ->body(__('panel/logs.actions.delete_all.notifications.success.body'))
                ->send();

            $superAdmins = User::superAdmins()->get();
            foreach ($superAdmins as $superAdmin) {
                $superAdmin->notify(
                    Notification::make()
                        ->title(Lang::get('panel/logs.actions.delete_all.notifications.success.title', [], 'fr'))
                        ->body(Lang::get('panel/logs.actions.delete_all.notifications.success.body', [], 'fr'))
                        ->toDatabase()
                );
            }
        } else {
            Notification::make()
                ->danger()
                ->title(__('panel/logs.actions.delete_all.notifications.error.title'))
                ->body(__('panel/logs.actions.delete_all.notifications.error.body'))
                ->send();
        }
    }
}
