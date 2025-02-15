<?php

namespace App\Filament\Resources\ExhibitorFormResource\Pages;

use App\Filament\Resources\ExhibitorFormResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class EditExhibitorForm extends EditRecord
{
    use NestedPage;

    protected static string $resource = ExhibitorFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
