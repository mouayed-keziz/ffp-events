<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected function getHeaderActions(): array
    {
        return [];
    }

    protected static string $resource = CategoryResource::class;
}
