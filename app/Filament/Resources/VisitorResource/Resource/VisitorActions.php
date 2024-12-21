<?php

namespace App\Filament\Resources\VisitorResource\Resource;

use Filament\Tables;
use App\Models\User;
use App\Actions\UserActions;
use Filament\Notifications\Notification;

class VisitorActions
{
    public static function regeneratePasswordTableAction()
    {
        return Tables\Actions\Action::make('regeneratePassword')
            ->iconButton()
            ->label(__('visitors.actions.regenerate_password.label'))
            ->icon('heroicon-o-key')
            ->requiresConfirmation()
            ->modalHeading(__('visitors.actions.regenerate_password.modal_heading'))
            ->modalDescription(__('visitors.actions.regenerate_password.modal_description'))
            ->action(function (User $record) {
                self::execute_action($record);
            });
    }

    public static function regeneratePasswordViewPageAction()
    {
        return \Filament\Actions\Action::make("regeneratePassword")
            ->label(__('visitors.actions.regenerate_password.label'))
            ->icon('heroicon-o-key')
            ->requiresConfirmation()
            ->modalHeading(__('visitors.actions.regenerate_password.modal_heading'))
            ->modalDescription(__('visitors.actions.regenerate_password.modal_description'))
            ->action(function (User $record) {
                self::execute_action($record);
            });
    }

    protected static function execute_action(User $record)
    {
        try {
            UserActions::regeneratePassword($record);

            Notification::make()
                ->success()
                ->title(__('visitors.actions.regenerate_password.success_title'))
                ->body(__('visitors.actions.regenerate_password.success_body'))
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title(__('visitors.actions.regenerate_password.error_title'))
                ->body(__('visitors.actions.regenerate_password.error_body'))
                ->send();
        }
    }
}
