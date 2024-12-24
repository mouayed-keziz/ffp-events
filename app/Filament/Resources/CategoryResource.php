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


class CategoryResource extends Resource
{
    use Translatable;

    protected static ?string $model = Category::class;
    protected static ?int $navigationSort = 6;
    protected static ?string $recordTitleAttribute = 'categoryTitle';
    public static function getNavigationGroup(): ?string
    {
        return __('nav.groups.articles');
    }

    public static function getModelLabel(): string
    {
        return __('articles.categories.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('articles.categories.plural');
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
