<?php

namespace App\Filament\Exports;

use App\Models\Article;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ArticleExporter extends Exporter
{
    protected static ?string $model = Article::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            // Translatable title field with language versions
            ExportColumn::make('title_ar')
                ->label('Title (Arabic)')
                ->state(fn(Article $record) => $record->getTranslation('title', 'ar')),
            ExportColumn::make('title_fr')
                ->label('Title (French)')
                ->state(fn(Article $record) => $record->getTranslation('title', 'fr')),
            ExportColumn::make('title_en')
                ->label('Title (English)')
                ->state(fn(Article $record) => $record->getTranslation('title', 'en')),

            // Translatable description field with language versions
            ExportColumn::make('description_ar')
                ->label('Description (Arabic)')
                ->state(fn(Article $record) => $record->getTranslation('description', 'ar')),
            ExportColumn::make('description_fr')
                ->label('Description (French)')
                ->state(fn(Article $record) => $record->getTranslation('description', 'fr')),
            ExportColumn::make('description_en')
                ->label('Description (English)')
                ->state(fn(Article $record) => $record->getTranslation('description', 'en')),

            // Translatable content field with language versions
            ExportColumn::make('content_ar')
                ->label('Content (Arabic)')
                ->state(fn(Article $record) => $record->getTranslation('content', 'ar')),
            ExportColumn::make('content_fr')
                ->label('Content (French)')
                ->state(fn(Article $record) => $record->getTranslation('content', 'fr')),
            ExportColumn::make('content_en')
                ->label('Content (English)')
                ->state(fn(Article $record) => $record->getTranslation('content', 'en')),

            // Categories by language
            ExportColumn::make('categories_ar')
                ->label('Categories (Arabic)')
                ->state(function (Article $record) {
                    return $record->categories->map(function ($category) {
                        return $category->getTranslation('name', 'ar');
                    })->join(', ');
                }),
            ExportColumn::make('categories_fr')
                ->label('Categories (French)')
                ->state(function (Article $record) {
                    return $record->categories->map(function ($category) {
                        return $category->getTranslation('name', 'fr');
                    })->join(', ');
                }),
            ExportColumn::make('categories_en')
                ->label('Categories (English)')
                ->state(function (Article $record) {
                    return $record->categories->map(function ($category) {
                        return $category->getTranslation('name', 'en');
                    })->join(', ');
                }),

            // Metrics
            ExportColumn::make('visits_count')
                ->label('Number of Visits')
                ->state(fn(Article $record) => $record->visits()->count()),

            ExportColumn::make('shares_count')
                ->label('Number of Shares')
                ->state(fn(Article $record) => $record->shares()->count()),

            // Other fields with better labels
            ExportColumn::make('slug')
                ->label('URL Slug'),

            ExportColumn::make('published_at')
                ->label('Publication Date'),

            ExportColumn::make('created_at')
                ->label('Created Date'),

            ExportColumn::make('updated_at')
                ->label('Last Updated'),

            ExportColumn::make('deleted_at')
                ->label('Deleted Date'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your article export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
