<?php


namespace App\Filament\Resources\ExhibitorFormResource\Components\Core;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class Options
{
    public static function make()
    {
        return Repeater::make('options')
            ->label('Options')
            ->columnSpan(2)
            ->collapsible()
            ->collapsed()
            ->schema([
                TextInput::make('option')
                    ->label('Option')
                    ->required()
                    ->translatable()
            ]);
    }
}
