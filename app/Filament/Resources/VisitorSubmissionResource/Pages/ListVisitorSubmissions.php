<?php

namespace App\Filament\Resources\VisitorSubmissionResource\Pages;

use App\Filament\Resources\VisitorSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class ListVisitorSubmissions extends ListRecords
{
    use NestedPage;

    protected static string $resource = VisitorSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
