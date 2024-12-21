<?php

namespace App\Filament\Resources\ExhibitorResource\Pages;

use App\Filament\Resources\ExhibitorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExhibitor extends EditRecord
{
    protected static string $resource = ExhibitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->icon("heroicon-o-eye"),
            Actions\DeleteAction::make()->icon("heroicon-o-trash"),
        ];
    }
}
