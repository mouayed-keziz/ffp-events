<?php

namespace App\Filament\Exports;

use App\Models\Log;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class LogExporter extends Exporter
{
    protected static ?string $model = Log::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('log_name')
                ->formatStateUsing(fn($state) => $state->value),
            ExportColumn::make('causer.name')
                ->label('Causer'),
            ExportColumn::make('event')
                ->formatStateUsing(fn($state) => $state->value),
            ExportColumn::make('subject.recordTitle')
                ->label('Subject')
                ->state(fn($record) => $record->subjectField),
            ExportColumn::make('created_at')
                ->formatStateUsing(fn($state) => $state->format('Y-m-d H:i:s')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your log export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
