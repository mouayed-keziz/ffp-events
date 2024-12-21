<?php

namespace App\Filament\Resources\ExhibitorResource\Resource;

use Filament\Forms;
use Filament\Forms\Form;

class ExhibitorForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label(__('exhibitor.form.name'))
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label(__('exhibitor.form.email'))
                        ->email()
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('password')
                        ->label(__('exhibitor.form.password'))
                        ->password()
                        ->required()
                        ->maxLength(255),
                ])
            ]);
    }
}
