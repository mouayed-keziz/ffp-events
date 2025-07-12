<?php

namespace App\Filament\Resources\ExhibitorFormResource\Components;

use App\Filament\Resources\ExhibitorFormResource\Components\Core\DescriptionInput;
use App\Filament\Resources\ExhibitorFormResource\Components\Core\LabelInput;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Toggle;

class CountrySelectBlock
{
    public static function make(string $name)
    {
        return Block::make($name)
            ->columns(2)
            ->schema([
                LabelInput::make(),
                DescriptionInput::make(),
                Toggle::make('required')
                    ->columnSpan(1)
                    ->default(true)
                    ->inline(false)
                    ->label(__('panel/forms.exhibitors.blocks.required')),
            ]);
    }
}
