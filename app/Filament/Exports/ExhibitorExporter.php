<?php

namespace App\Filament\Exports;

use App\Models\Exhibitor;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ExhibitorExporter extends Exporter
{
    protected static ?string $model = Exhibitor::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('name')
                ->label('Name'),

            ExportColumn::make('email')
                ->label('Email Address'),

            // Fix Currency enum conversion to string
            ExportColumn::make('currency')
                ->label('Currency')
                ->state(function (Exhibitor $record) {
                    // Convert Currency enum to string
                    return $record->currency ? $record->currency->value : null;
                }),

            // Add submissions count
            ExportColumn::make('submissions_count')
                ->label('Number of Submissions')
                ->state(fn(Exhibitor $record) => $record->submissions()->count()),

            ExportColumn::make('verified_at')
                ->label('Verification Date'),

            ExportColumn::make('new_email')
                ->label('New Email Address'),

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
        $body = 'Your exhibitor export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
