<?php

namespace App\Filament\Resources\ExhibitorFormResource\Components;

use App\Enums\Currency;
use App\Filament\Resources\ExhibitorFormResource\Components\Core\DescriptionInput;
use App\Filament\Resources\ExhibitorFormResource\Components\Core\LabelInput;
use App\Filament\Resources\ExhibitorFormResource\Components\Core\OptionsPriced;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\KeyValue;

class CheckboxBlockPriced
{
    public static function make(string $name, $currencies = [])
    {
        $fixedCurrencies = [
            Currency::EUR->value => 0,
            Currency::USD->value => 0,
            Currency::DA->value  => 0,
        ];

        return Block::make($name)
            ->columns(2)
            ->schema([
                LabelInput::make(),
                DescriptionInput::make(),
                OptionsPriced::make()
            ]);
    }
}
