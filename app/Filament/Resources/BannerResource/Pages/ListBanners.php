<?php

namespace App\Filament\Resources\BannerResource\Pages;

use App\Enums\Role;
use App\Filament\Resources\BannerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBanners extends ListRecords
{
    protected static string $resource = BannerResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->hasRole(Role::SUPER_ADMIN->value);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
