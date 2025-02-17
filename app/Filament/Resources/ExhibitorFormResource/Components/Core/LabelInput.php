<?php


namespace App\Filament\Resources\ExhibitorFormResource\Components\Core;

use Filament\Forms\Components\TextInput;

class LabelInput
{
    public static function make()
    {
        return TextInput::make('label')
            ->label('Label')
            ->required()
            ->translatable();
    }
}
