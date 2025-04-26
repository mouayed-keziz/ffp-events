<?php

namespace App\Filament\Resources\LogResource\Pages;

use App\Actions\LogActions;
use App\Enums\Role;
use App\Filament\Exports\LogExporter;
use App\Filament\Resources\LogResource;

use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;


class ListLogs extends ListRecords
{
    protected static string $resource = LogResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->hasRole(Role::SUPER_ADMIN->value);
    }

    protected function getHeaderActions(): array
    {
        return [
            // ExportAction::make()
            //     ->label(__("panel/logs.actions.export.label"))
            //     ->icon('heroicon-o-arrow-down-tray')
            //     ->exporter(LogExporter::class),
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
                    LogActions::deleteAllLogs($data['password']);
                })
                ->modalSubmitActionLabel(__('panel/logs.actions.delete_all.modal.submit_label'))
                ->stickyModalHeader()
        ];
    }
}
