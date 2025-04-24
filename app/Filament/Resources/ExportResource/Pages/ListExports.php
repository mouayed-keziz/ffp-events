<?php

namespace App\Filament\Resources\ExportResource\Pages;

use App\Enums\Role;
use App\Filament\Resources\ExportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExports extends ListRecords
{
    protected static string $resource = ExportResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->hasRole(Role::SUPER_ADMIN->value);
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
