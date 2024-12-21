<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExhibitorResource\Pages;
use App\Filament\Resources\ExhibitorResource\RelationManagers;
use App\Filament\Resources\ExhibitorResource\Resource\ExhibitorForm;
use App\Filament\Resources\ExhibitorResource\Resource\ExhibitorInfolist;
use App\Filament\Resources\ExhibitorResource\Resource\ExhibitorTable;
use App\Filament\Resources\UserResource\Resource\UserTable;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExhibitorResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationBadge(): ?string
    {
        return User::exhibitors()->count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }
    protected static ?string $recordTitleAttribute = 'exhibitorTitle';
    protected static bool $shouldRegisterNavigation = true;
    public static function getNavigationGroup(): ?string
    {
        return __('nav.groups.management');
    }

    public static function getModelLabel(): string
    {
        return __('exhibitors.resource.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('exhibitors.resource.plural');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }

    public static function form(Form $form): Form
    {
        return ExhibitorForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return ExhibitorTable::table($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ExhibitorInfolist::infolist($infolist);
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
            'index' => Pages\ListExhibitors::route('/'),
            'create' => Pages\CreateExhibitor::route('/create'),
            'view' => Pages\ViewExhibitor::route('/{record}'),
            'edit' => Pages\EditExhibitor::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->exhibitors()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
