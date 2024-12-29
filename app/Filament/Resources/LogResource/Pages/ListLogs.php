<?php

namespace App\Filament\Resources\LogResource\Pages;

use App\Filament\Exports\LogExporter;
use App\Filament\Resources\LogResource;
use App\Models\Log;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;

class ListLogs extends ListRecords
{
    protected static string $resource = LogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label(__("panel/logs.actions.export.label"))
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(LogExporter::class),
            Actions\Action::make("delete-all-logs")
                ->outlined()
                ->label(__('panel/logs.actions.delete_all.label'))
                ->icon("heroicon-o-trash")
                ->color("danger")
                ->requiresConfirmation()
                ->modalHeading(__('panel/logs.actions.delete_all.modal.heading'))
                ->modalDescription(__('panel/logs.actions.delete_all.modal.description'))
                ->modalIcon('heroicon-o-exclamation-triangle')
                ->modalIconColor('danger')
                ->form([
                    TextInput::make("password")
                        ->label(__('panel/logs.actions.delete_all.modal.password.label'))
                        ->helperText(__('panel/logs.actions.delete_all.modal.password.helper_text'))
                        ->password()
                        ->required()
                ])
                ->action(function (array $data): void {
                    if (Hash::check($data["password"], Auth::user()->password)) {
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
                })
                ->modalSubmitActionLabel(__('panel/logs.actions.delete_all.modal.submit_label'))
                ->stickyModalHeader()
        ];
    }
}
