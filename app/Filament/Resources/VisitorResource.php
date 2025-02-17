<?php

namespace App\Filament\Resources;

use App\Filament\Navigation\Sidebar;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VisitorResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VisitorResource\Resource\VisitorForm;
use App\Filament\Resources\VisitorResource\Resource\VisitorTable;
use App\Filament\Resources\VisitorResource\Resource\VisitorInfolist;
use App\Models\Visitor;

class VisitorResource extends Resource
{
    protected static ?string $model = Visitor::class;

    protected static ?string $navigationIcon = Sidebar::VISITOR["icon"];
    protected static ?int $navigationSort = Sidebar::VISITOR["sort"];
    protected static ?string $recordTitleAttribute = 'visitorTitle';
    protected static bool $shouldRegisterNavigation = true;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
        return __('panel/visitors.resource.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel/visitors.resource.plural');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }

    public static function form(Form $form): Form
    {
        return VisitorForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return VisitorTable::table($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return VisitorInfolist::infolist($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVisitors::route('/'),
            'create' => Pages\CreateVisitor::route('/create'),
            'view' => Pages\ViewVisitor::route('/{record}'),
            'edit' => Pages\EditVisitor::route('/{record}/edit'),
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
