<?php

namespace App\Filament\Resources\ExhibitorResource\Pages;

use App\Filament\Resources\ExhibitorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewExhibitor extends ViewRecord
{
    protected static string $resource = ExhibitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
