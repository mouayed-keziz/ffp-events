<?php

namespace App\Filament\Resources\ExhibitorFormResource\Components;

use App\Enums\Currency;
use App\Filament\Resources\ExhibitorFormResource\Components\Core\DescriptionInput;
use App\Filament\Resources\ExhibitorFormResource\Components\Core\LabelInput;
use App\Filament\Resources\ExhibitorFormResource\Components\Core\OptionsPriced;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\KeyValue;

class RadioBlockPriced
{
    public static function make(string $name, $currencies = [])
    {
        return Block::make($name)
            ->columns(2)
            ->schema([
                LabelInput::make(),
                DescriptionInput::make(),
                Toggle::make('required')
                    ->label(__('panel/forms.exhibitors.blocks.required'))
                    ->default(false)
                    ->columnSpanFull(),
                OptionsPriced::make()
            ]);
    }
}
