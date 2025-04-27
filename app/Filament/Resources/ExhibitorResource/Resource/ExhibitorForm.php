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
                        ->label(__('panel/exhibitors.form.name'))
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label(__('panel/exhibitors.form.email'))
                        ->email()
                        ->unique(table: 'users')
                        ->unique(table: 'exhibitors', ignorable: fn($record) => $record)
                        ->unique(table: 'visitors')
                        ->required()
                        ->maxLength(255),

                    // Password field removed for auto-generation
                ])
            ]);
    }
}
