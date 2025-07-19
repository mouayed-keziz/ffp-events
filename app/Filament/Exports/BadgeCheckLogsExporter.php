<?php

namespace App\Filament\Exports;

use App\Models\BadgeCheckLog;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class BadgeCheckLogsExporter extends Exporter
{
    protected static ?string $model = BadgeCheckLog::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('badge_name')
                ->label('Name'),
            ExportColumn::make('badge_email')
                ->label('Email'),
            ExportColumn::make('badge_company')
                ->label('Company'),
            ExportColumn::make('action')
                ->label('Action')
                ->formatStateUsing(fn($state) => $state ? $state->value : ''),
            ExportColumn::make('action_time')
                ->label('Action Time'),
            ExportColumn::make('checkedByUser.name')
                ->label('Checked By'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your badge check logs export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
