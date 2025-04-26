<?php

namespace App\Filament\Exports;

use App\Models\Export as ExportModel;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ExportExporter extends Exporter
{
    protected static ?string $model = ExportModel::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('exporter')
                ->label('Export Type')
                ->state(function (ExportModel $record) {
                    // Handle the ExportType enum properly
                    return $record->exporter ? $record->exporter->getLabel() : null;
                }),

            ExportColumn::make('exported_by_name')
                ->label('Exported By')
                ->state(fn(ExportModel $record) => $record->exported_by?->name),

            ExportColumn::make('exported_by_email')
                ->label('Exporter Email')
                ->state(fn(ExportModel $record) => $record->exported_by?->email),

            ExportColumn::make('file_name')
                ->label('File Name'),

            ExportColumn::make('file_disk')
                ->label('Storage Disk'),

            ExportColumn::make('processed_rows')
                ->label('Processed Rows'),

            ExportColumn::make('total_rows')
                ->label('Total Rows'),

            ExportColumn::make('successful_rows')
                ->label('Successful Rows'),

            ExportColumn::make('completed_at')
                ->label('Completion Date'),

            ExportColumn::make('created_at')
                ->label('Created Date'),

            ExportColumn::make('updated_at')
                ->label('Last Updated'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your export export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
