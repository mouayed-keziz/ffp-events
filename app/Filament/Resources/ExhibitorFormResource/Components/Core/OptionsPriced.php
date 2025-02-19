<?php


namespace App\Filament\Resources\ExhibitorFormResource\Components\Core;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class OptionsPriced
{
    public static function make()
    {
        return Repeater::make('options')
            ->label('Options')
            ->columnSpan(2)
            ->collapsible()
            ->collapsed()
            ->itemLabel(function ($state) {
                return "Option" . ($state['option'] ? ": " . ($state['option'][app()->getLocale()] ?? '') : '');
            })
            ->schema([
                TextInput::make('option')
                    ->label('Option')
                    ->required()
                    ->translatable(),

                PriceInput::make()
            ]);
    }
}
