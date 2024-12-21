<?php

namespace App\Filament\Resources\UserResource\Resource;

use Filament\Tables;
use App\Models\User;
use App\Actions\UserActions as UserActionsService;
use Filament\Notifications\Notification;

class UserActions
{
    public static function regeneratePasswordTableAction()
    {
        return Tables\Actions\Action::make('regeneratePassword')
            ->iconButton()
            ->label(__('users.actions.regenerate_password.label'))
            ->icon('heroicon-o-key')
            ->requiresConfirmation()
            ->modalHeading(__('users.actions.regenerate_password.modal_heading'))
            ->modalDescription(__('users.actions.regenerate_password.modal_description'))
            ->action(function (User $record) {
                self::execute_action($record);
            });
    }

    public static function regeneratePasswordViewPageAction()
    {
        return \Filament\Actions\Action::make("regeneratePassword")
            ->label(__('users.actions.regenerate_password.label'))
            ->icon('heroicon-o-key')
            ->requiresConfirmation()
            ->modalHeading(__('users.actions.regenerate_password.modal_heading'))
            ->modalDescription(__('users.actions.regenerate_password.modal_description'))
            ->action(function (User $record) {
                self::execute_action($record);
            });
    }

    protected static function execute_action(User $record)
    {
        try {
            UserActionsService::regeneratePassword($record);

            Notification::make()
                ->success()
                ->title(__('users.actions.regenerate_password.success_title'))
                ->body(__('users.actions.regenerate_password.success_body'))
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title(__('users.actions.regenerate_password.error_title'))
                ->body(__('users.actions.regenerate_password.error_body'))
                ->send();
        }
    }
}
