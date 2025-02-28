<?php

namespace App\Filament\Resources\PlanTierResource\Pages;

use App\Filament\Resources\PlanTierResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlanTiers extends ListRecords
{
    protected static string $resource = PlanTierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
