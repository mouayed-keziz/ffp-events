<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Filament\Resources\CategoryResource\RelationManagers\ArticlesRelationManager;
use App\Filament\Resources\CategoryResource\Resource\CategoryForm;
use App\Filament\Resources\CategoryResource\Resource\CategoryTable;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Concerns\Translatable;
use App\Filament\Navigation\Sidebar;

class CategoryResource extends Resource
{
    use Translatable;

    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = Sidebar::CATEGORY['icon'];
    protected static ?int $navigationSort = Sidebar::CATEGORY['sort'];
    protected static ?string $recordTitleAttribute = 'recordTitle';

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
        return __(Sidebar::CATEGORY['group']);
    }

    public static function getModelLabel(): string
    {
        return __('panel/articles.categories.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel/articles.categories.plural');
    }

    public static function form(Form $form): Form
    {
        return CategoryForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return CategoryTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            ArticlesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
