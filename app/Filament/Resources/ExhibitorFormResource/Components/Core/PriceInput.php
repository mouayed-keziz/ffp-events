<?php

namespace App\Filament\Resources\ExhibitorFormResource\Components\Core;

use App\Enums\Currency;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Lang;

class PriceInput
{
    public static function make()
    {
        $fixedCurrencies = [
            Currency::EUR->value => 0,
            Currency::USD->value => 0,
            Currency::DA->value  => 0,
        ];
        return Fieldset::make('price')
            ->label(__('panel/forms.exhibitors.blocks.price'))
            ->schema([
                TextInput::make('price.' . Currency::DA->value)
                    ->label(Currency::DA->value)
                    ->numeric()
                    ->default(0),
                TextInput::make('price.' . Currency::EUR->value)
                    ->label(Currency::EUR->value)
                    ->numeric()
                    ->default(0),
                TextInput::make('price.' . Currency::USD->value)
                    ->label(Currency::USD->value)
                    ->numeric()
                    ->default(0),
            ])
            ->columns(3)
            ->columnSpanFull();
    }
}
