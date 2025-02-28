<?php

namespace App\Filament\Resources\PlanTierResource\Pages;

use App\Filament\Resources\PlanTierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlanTier extends EditRecord
{
    protected static string $resource = PlanTierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
