<?php


namespace App\Filament\Resources\ExhibitorFormResource\Components\Core;

use Filament\Forms\Components\TextInput;

class DescriptionInput
{
    public static function make()
    {
        return TextInput::make('description')
            ->label('Description')
            ->translatable();
    }
}
