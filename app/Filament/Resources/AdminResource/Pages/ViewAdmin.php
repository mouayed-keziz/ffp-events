<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Filament\Resources\AdminResource;
use App\Filament\Resources\AdminResource\Resource\AdminActions;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAdmin extends ViewRecord
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            AdminActions::regeneratePasswordViewPageAction(),
            Actions\EditAction::make()->icon("heroicon-o-pencil"),
        ];
    }
}
