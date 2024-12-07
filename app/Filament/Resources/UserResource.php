<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\Resource\UserForm;
use App\Filament\Resources\UserResource\Resource\UserTable;
use App\Filament\Resources\UserResource\Resource\UserInfolist;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $shouldRegisterNavigation = true;

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

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }

    public static function form(Form $form): Form
    {
        return UserForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return UserTable::table($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return UserInfolist::infolist($infolist);
    }

    public static function getRelations(): array
    {
        return [];
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
