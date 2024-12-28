<?php

namespace App\Filament\Resources\ExportResource\Pages;

use App\Filament\Resources\ExportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExport extends EditRecord
{
    protected static string $resource = ExportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
