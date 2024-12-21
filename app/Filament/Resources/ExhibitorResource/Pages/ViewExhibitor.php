<?php

namespace App\Filament\Resources\ExhibitorResource\Pages;

use App\Filament\Resources\ExhibitorResource;
use App\Filament\Resources\UserResource\Resource\ExhibitorActions;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewExhibitor extends ViewRecord
{
    protected static string $resource = ExhibitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExhibitorActions::regeneratePasswordViewPageAction(),
            Actions\EditAction::make()->icon("heroicon-o-pencil"),
        ];
    }
}
