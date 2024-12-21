<?php

namespace App\Filament\Resources\UserResource\Resource;

use Filament\Tables;
use App\Models\User;
use App\Actions\UserActions as UserActionsService;
use Filament\Notifications\Notification;

class ExhibitorActions
{
    public static function regeneratePasswordTableAction()
    {
        return Tables\Actions\Action::make('regeneratePassword')
            ->iconButton()
            ->label(__('exhibitor.actions.regenerate_password.label'))
            ->icon('heroicon-o-key')
            ->requiresConfirmation()
            ->modalHeading(__('exhibitor.actions.regenerate_password.modal_heading'))
            ->modalDescription(__('exhibitor.actions.regenerate_password.modal_description'))
            ->action(function (User $record) {
                self::execute_action($record);
            });
    }

    public static function regeneratePasswordViewPageAction()
    {
        return \Filament\Actions\Action::make("regeneratePassword")
            ->label(__('exhibitor.actions.regenerate_password.label'))
            ->icon('heroicon-o-key')
            ->requiresConfirmation()
            ->modalHeading(__('exhibitor.actions.regenerate_password.modal_heading'))
            ->modalDescription(__('exhibitor.actions.regenerate_password.modal_description'))
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
                ->title(__('exhibitor.actions.regenerate_password.success_title'))
                ->body(__('exhibitor.actions.regenerate_password.success_body'))
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title(__('exhibitor.actions.regenerate_password.error_title'))
                ->body(__('exhibitor.actions.regenerate_password.error_body'))
                ->send();
        }
    }
}
