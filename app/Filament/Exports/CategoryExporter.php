<?php

namespace App\Filament\Exports;

use App\Models\Category;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class CategoryExporter extends Exporter
{
    protected static ?string $model = Category::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            // Translatable name field with language versions
            ExportColumn::make('name_ar')
                ->label('Name (Arabic)')
                ->state(fn(Category $record) => $record->getTranslation('name', 'ar')),
            ExportColumn::make('name_fr')
                ->label('Name (French)')
                ->state(fn(Category $record) => $record->getTranslation('name', 'fr')),
            ExportColumn::make('name_en')
                ->label('Name (English)')
                ->state(fn(Category $record) => $record->getTranslation('name', 'en')),

            ExportColumn::make('slug')
                ->label('URL Slug'),

            // Article count
            ExportColumn::make('articles_count')
                ->label('Number of Articles')
                ->state(fn(Category $record) => $record->articles()->count()),

            ExportColumn::make('created_at')
                ->label('Created Date'),

            ExportColumn::make('updated_at')
                ->label('Last Updated'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your category export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
