<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\Resource\ArticleForm;
use App\Filament\Resources\ArticleResource\Resource\ArticleTable;
use App\Filament\Resources\ArticleResource\Resource\ArticleInfolist;
use App\Models\Article;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    // protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?int $navigationSort = 5;
    protected static ?string $recordTitleAttribute = 'articleTitle';

    public static function getNavigationGroup(): ?string
    {
        return __('nav.groups.articles');
    }

    public static function getModelLabel(): string
    {
        return __('articles.resource.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('articles.resource.plural');
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'description', "content"];
    }
    public static function form(Form $form): Form
    {
        return ArticleForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return ArticleTable::table($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ArticleInfolist::infolist($infolist);
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'view' => Pages\ViewArticle::route('/{record}'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
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
