<?php

namespace App\Filament\Resources\VisitorResource\Resource;

use Filament\Forms;
use Filament\Forms\Form;

class VisitorForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label(__('panel/visitors.form.name'))
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label(__('panel/visitors.form.email'))
                        ->email()
                        ->unique(table: 'users')
                        ->unique(table: 'exhibitors')
                        ->unique(table: 'visitors', ignorable: fn($record) => $record)
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('password')
                        ->label(__('panel/visitors.form.password'))
                        ->password()
                        ->required()
                        ->maxLength(255),
                ])
            ]);
    }
}
