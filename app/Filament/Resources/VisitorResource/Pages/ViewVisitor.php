<?php

namespace App\Filament\Resources\VisitorResource\Pages;

use App\Filament\Resources\VisitorResource;
use App\Filament\Resources\VisitorResource\Resource\VisitorActions;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewVisitor extends ViewRecord
{
    protected static string $resource = VisitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            VisitorActions::regeneratePasswordViewPageAction(),
            Actions\EditAction::make()->icon("heroicon-o-pencil"),
        ];
    }
}
