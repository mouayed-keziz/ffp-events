<?php

namespace App\Filament\Resources\ExhibitorFormResource\Components;

use App\Filament\Resources\ExhibitorFormResource\Components\Core\DescriptionInput;
use App\Filament\Resources\ExhibitorFormResource\Components\Core\LabelInput;
use App\Filament\Resources\ExhibitorFormResource\Components\Core\Options;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class RadioBlock
{
    public static function make(string $name)
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
                Options::make()
            ]);
    }
}
