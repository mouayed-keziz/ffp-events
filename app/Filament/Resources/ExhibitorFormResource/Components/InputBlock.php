<?php

namespace App\Filament\Resources\ExhibitorFormResource\Components;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use App\Enums\FormInputType;
use App\Filament\Resources\ExhibitorFormResource\Components\Core\DescriptionInput;
use App\Filament\Resources\ExhibitorFormResource\Components\Core\LabelInput;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Lang;

class InputBlock
{
    public static function make(string $name)
    {

        return Block::make($name)
            ->columns(2)
            ->schema([
                LabelInput::make(),
                DescriptionInput::make(),

                Group::make()->columnSpan(2)->columns(10)->schema([
                    Select::make('type')
                        ->columnSpan(8)
                        ->required()
                        ->label(__('panel/forms.exhibitors.blocks.type'))
                        // ->native(false)
                        ->options(FormInputType::class),

                    Toggle::make('required')
                        ->columnSpan(1)
                        ->default(true)
                        ->inline(false)
                        ->label(__('panel/forms.exhibitors.blocks.required')),
                ])
            ]);
    }
}
