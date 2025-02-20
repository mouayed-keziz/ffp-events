<?php


namespace App\Filament\Resources\ExhibitorFormResource\Components\Core;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Lang;

class Options
{
    public static function make()
    {

        return Repeater::make('options')
            ->label(__('panel/forms.exhibitors.blocks.options'))
            ->columnSpan(2)
            ->collapsible()
            ->collapsed()
            ->itemLabel(function ($state) {
                return __('panel/forms.exhibitors.blocks.option') . ($state['option'] ? ": " . ($state['option'][app()->getLocale()] ?? '') : '');
            })
            ->schema([
                TextInput::make('option')
                    ->label(__('panel/forms.exhibitors.blocks.option'))
                    ->required()
                    ->translatable()
            ]);
    }
}
