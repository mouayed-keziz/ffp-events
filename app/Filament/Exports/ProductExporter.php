<?php

namespace App\Filament\Exports;

use App\Models\Product;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            // Translatable name field with language versions
            ExportColumn::make('name_ar')
                ->label('Name (Arabic)')
                ->state(fn(Product $record) => $record->getTranslation('name', 'ar')),
            ExportColumn::make('name_fr')
                ->label('Name (French)')
                ->state(fn(Product $record) => $record->getTranslation('name', 'fr')),
            ExportColumn::make('name_en')
                ->label('Name (English)')
                ->state(fn(Product $record) => $record->getTranslation('name', 'en')),

            ExportColumn::make('code')
                ->label('Product Code'),

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
        $body = 'Your product export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
