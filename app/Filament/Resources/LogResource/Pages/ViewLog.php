<?php

namespace App\Filament\Resources\LogResource\Pages;

use App\Filament\Resources\LogResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLog extends ViewRecord
{
    protected static string $resource = LogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make()->icon("heroicon-o-pencil"),
        ];
    }
}
