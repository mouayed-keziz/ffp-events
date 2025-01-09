<?php

namespace App\Filament\Resources\AdminResource\Resource;

use Filament\Forms;
use Filament\Forms\Form;

class AdminForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Section::make("hello")
                        ->schema([
                            Forms\Components\KeyValue::make('test')
                                ->label('Test')
                                ->keyLabel('Currency')
                                ->valueLabel('Price'),
                        ]),
                    Forms\Components\TextInput::make('name')
                        ->label(__('panel/admins.form.name'))
                        ->required()
                        ->maxLength(255)
                        ->suffixAction(
                            Forms\Components\Actions\Action::make('setStaticPrices')
                                ->label('Set Static Prices')
                                ->icon('heroicon-o-plus')
                                ->action(function ($set) {
                                    // Set static prices for the KeyValue input
                                    $staticPrices = [
                                        'EUR' => 100,
                                        'USD' => 120,
                                    ];
                                    $set('test', $staticPrices);
                                })
                        ),

                    Forms\Components\TextInput::make('email')
                        ->label(__('panel/admins.form.email'))
                        ->email()
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('password')
                        ->label(__('panel/admins.form.password'))
                        ->password()
                        ->required()
                        ->maxLength(255),
                ])
            ]);
    }
}
