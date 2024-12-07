<?php

namespace App\Filament\Resources\UserResource\Resource;

use Filament\Forms;
use Filament\Forms\Form;

class UserForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label(__('users.form.name'))
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label(__('users.form.email'))
                        ->email()
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('password')
                        ->label(__('users.form.password'))
                        ->password()
                        ->required()
                        ->maxLength(255),
                ])
            ]);
    }
}
