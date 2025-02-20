<?php

namespace App\Filament\Resources\ExhibitorFormResource\Components\Core;

use App\Enums\Currency;
use Filament\Forms\Components\KeyValue;
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
        return  KeyValue::make('price')
            ->columnSpanFull()
            ->label(__('panel/forms.exhibitors.blocks.price'))
            ->columns(3)
            ->default($fixedCurrencies)
            ->live() // activate live updates
            ->afterStateUpdated(function ($state, callable $set, $component) {
                $filtered = [];
                foreach ($state as $key => $value) {
                    if (is_numeric($value)) {
                        $filtered[$key] = $value;
                    } else {
                        $filtered[$key] = 0;
                    }
                }
                // Use component method to update state
                $component->state($filtered);
            });
    }
}
