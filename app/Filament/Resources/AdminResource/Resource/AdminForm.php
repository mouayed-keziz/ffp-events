<?php

namespace App\Filament\Resources\AdminResource\Resource;

use App\Enums\Role;
use Filament\Forms;
use Filament\Forms\Form;

class AdminForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([

                    Forms\Components\TextInput::make('name')
                        ->label(__('panel/admins.form.name'))
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label(__('panel/admins.form.email'))
                        ->email()
                        ->required()
                        ->unique(table: 'users', ignorable: fn($record) => $record)
                        ->unique(table: 'exhibitors')
                        ->unique(table: 'visitors')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('password')
                        ->label(__('panel/admins.form.password'))
                        ->password()
                        ->required(fn(string $operation): bool => $operation === 'create')
                        ->visible(fn(string $operation): bool => $operation === 'create')
                        ->maxLength(255),

                    Forms\Components\Select::make('roles')
                        ->label(__('panel/admins.form.roles'))
                        ->placeholder(__('panel/admins.empty_states.roles'))
                        ->relationship('roles', 'name')
                        ->getOptionLabelFromRecordUsing(fn(\App\Models\Role $record) => $record->formatted_name),

                    Forms\Components\Select::make('assignedEvents')
                        ->label(__('panel/admins.form.assigned_events'))
                        ->placeholder(__('panel/admins.empty_states.assigned_events'))
                        ->relationship('assignedEvents', 'title')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->visible(function ($record) {
                            // Only show for hostess role
                            if (!$record) return false;
                            return $record->hasRole(Role::HOSTESS->value);
                        }),
                ])
            ]);
    }
}
