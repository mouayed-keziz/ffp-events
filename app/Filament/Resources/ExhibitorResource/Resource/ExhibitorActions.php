<?php

namespace App\Filament\Resources\ExhibitorResource\Resource;

use Filament\Tables;
use App\Models\User;
use App\Actions\UserActions as UserActionsService;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class ExhibitorActions
{
    public static function regeneratePasswordTableAction()
    {
        return Tables\Actions\Action::make('regeneratePassword')
            ->button()
            ->outlined()
            ->label(__('panel/exhibitors.actions.regenerate_password.label'))
            ->icon('heroicon-o-key')
            ->requiresConfirmation()
            ->modalHeading(__('panel/exhibitors.actions.regenerate_password.modal_heading'))
            ->modalDescription(__('panel/exhibitors.actions.regenerate_password.modal_description'))
            ->action(function (Model $record) {
                self::execute_action($record);
            });
    }

    public static function regeneratePasswordViewPageAction()
    {
        return \Filament\Actions\Action::make("regeneratePassword")
            ->label(__('panel/exhibitors.actions.regenerate_password.label'))
            ->icon('heroicon-o-key')
            ->requiresConfirmation()
            ->modalHeading(__('panel/exhibitors.actions.regenerate_password.modal_heading'))
            ->modalDescription(__('panel/exhibitors.actions.regenerate_password.modal_description'))
            ->action(function (Model $record) {
                self::execute_action($record);
            });
    }

    protected static function execute_action(Model $record)
    {
        try {
            UserActionsService::regeneratePassword($record);

            Notification::make()
                ->success()
                ->title(__('panel/exhibitors.actions.regenerate_password.success_title'))
                ->body(__('panel/exhibitors.actions.regenerate_password.success_body'))
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title(__('panel/exhibitors.actions.regenerate_password.error_title'))
                ->body(__('panel/exhibitors.actions.regenerate_password.error_body'))
                ->send();
        }
    }
}
