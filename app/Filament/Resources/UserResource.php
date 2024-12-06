<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): ?string
    {
        return __('nav.groups.management');
    }

    public static function getModelLabel(): string
    {
        return __('users.resource.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('users.resource.plural');
    }

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('users.columns.name'))
                    ->searchable()
                    ->default(__('users.empty_states.name')),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('users.columns.email'))
                    ->searchable()
                    ->default(__('users.empty_states.email')),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('users.columns.roles'))
                    ->badge()

                    ->default(__('users.empty_states.roles')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('users.columns.created_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('users.columns.updated_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->emptyStateHeading(__('users.empty_states.title'))
            ->emptyStateDescription(__('users.empty_states.description'))
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make([
                    Infolists\Components\TextEntry::make('name')
                        ->label(__('users.form.name'))
                        ->default(__('users.empty_states.name')),
                    Infolists\Components\TextEntry::make('email')
                        ->label(__('users.form.email'))
                        ->default(__('users.empty_states.email')),
                    Infolists\Components\TextEntry::make('roles.name')
                        ->label(__('users.columns.roles'))
                        ->badge()
                        ->color("gray")
                        ->default(__('users.empty_states.roles')),
                    Infolists\Components\TextEntry::make('created_at')
                        ->label(__('users.columns.created_at'))
                        ->dateTime(),
                    Infolists\Components\TextEntry::make('updated_at')
                        ->label(__('users.columns.updated_at'))
                        ->dateTime(),
                ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
