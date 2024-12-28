<?php

namespace App\Filament\Resources\ExportResource\Pages;

use App\Filament\Resources\ExportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExports extends ListRecords
{
    protected static string $resource = ExportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
