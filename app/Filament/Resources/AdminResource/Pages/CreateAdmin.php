<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Enums\Role;
use App\Filament\Resources\AdminResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAdmin extends CreateRecord
{
    protected static string $resource = AdminResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->hasRole(Role::SUPER_ADMIN->value);
    }
}
