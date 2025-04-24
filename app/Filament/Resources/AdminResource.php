<?php

namespace App\Filament\Resources;

use App\Filament\Navigation\Sidebar;
use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;
use App\Filament\Resources\AdminResource\Resource\AdminForm;
use App\Filament\Resources\AdminResource\Resource\AdminInfolist;
use App\Filament\Resources\AdminResource\Resource\AdminTable;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdminResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = Sidebar::ADMIN["icon"];
    protected static ?int $navigationSort = Sidebar::ADMIN["sort"];
    protected static ?string $recordTitleAttribute = 'adminTitle';
    public static function getNavigationBadge(): ?string
    {
        return User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'super_admin']);
        })->count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }
    public static function getNavigationGroup(): ?string
    {
        return __('panel/nav.groups.users');
    }

    public static function getModelLabel(): string
    {
        return __('panel/admins.resource.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel/admins.resource.plural');
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
    public static function form(Form $form): Form
    {
        return AdminForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return AdminTable::table($table);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return AdminInfolist::infolist($infolist);
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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'view' => Pages\ViewAdmin::route('/{record}'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            // ->whereHas('roles', function ($query) {
            //     $query->whereIn('name', ['admin', 'super_admin']);
            // })
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
