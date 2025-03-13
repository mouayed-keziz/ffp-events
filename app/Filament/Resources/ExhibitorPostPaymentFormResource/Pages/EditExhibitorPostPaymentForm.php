<?php

namespace App\Filament\Resources\ExhibitorPostPaymentFormResource\Pages;

use App\Filament\Resources\ExhibitorPostPaymentFormResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class EditExhibitorPostPaymentForm extends EditRecord
{
    use NestedPage;

    protected static string $resource = ExhibitorPostPaymentFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
