<?php

namespace App\Filament\Resources\PlanTierResource\Pages;

use App\Filament\Resources\PlanTierResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPlanTier extends ViewRecord
{
    protected static string $resource = PlanTierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->icon("heroicon-o-pencil"),
        ];
    }
}
