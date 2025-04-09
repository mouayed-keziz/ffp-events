<?php

namespace App\Filament\Resources\ExhibitorFormResource\Components;

use App\Filament\Resources\ExhibitorFormResource\Components\Core\DescriptionInput;
use App\Filament\Resources\ExhibitorFormResource\Components\Core\LabelInput;
use App\Models\PlanTier;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;

class PlanTierCheckboxBlock
{
    public static function make(string $name)
    {
        return Block::make($name)
            ->columns(2)
            ->schema([
                LabelInput::make(),
                DescriptionInput::make(),
                Select::make('plan_tier_id')
                    ->label(trans('panel/plan.tier.single'))
                    ->options(function () {
                        return PlanTier::all()->pluck('title', 'id');
                    })
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
