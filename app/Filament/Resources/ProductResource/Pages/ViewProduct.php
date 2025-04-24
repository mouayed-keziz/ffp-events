<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->icon("heroicon-o-pencil"),
            Actions\ForceDeleteAction::make()->icon("heroicon-o-trash"),
            Actions\RestoreAction::make()->icon("heroicon-o-arrow-path"),
        ];
    }
}
