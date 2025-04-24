<?php

namespace App\Filament\Resources\ExhibitorPostPaymentFormResource\Pages;

use App\Enums\Role;
use App\Filament\Resources\ExhibitorPostPaymentFormResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class EditExhibitorPostPaymentForm extends EditRecord
{
    use NestedPage;

    protected static string $resource = ExhibitorPostPaymentFormResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->hasRole(Role::SUPER_ADMIN->value);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
