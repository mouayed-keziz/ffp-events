<?php

namespace App\Filament\Resources\ExhibitorSubmissionResource\Pages;

use App\Filament\Resources\ExhibitorSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExhibitorSubmissions extends ListRecords
{
    protected static string $resource = ExhibitorSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
