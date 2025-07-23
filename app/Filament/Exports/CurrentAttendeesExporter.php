<?php

namespace App\Filament\Exports;

use App\Models\CurrentAttendee;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class CurrentAttendeesExporter extends Exporter
{
    protected static ?string $model = CurrentAttendee::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('badge_name')
                ->label('Name'),
            ExportColumn::make('badge_email')
                ->label('Email'),
            ExportColumn::make('badge_company')
                ->label('Company'),
            ExportColumn::make('badge_position')
                ->label('Position'),
            ExportColumn::make('checked_in_at')
                ->label('Checked In At'),
            ExportColumn::make('status')
                ->formatStateUsing(fn($state) => $state ? $state->value : '')
                ->label('Status'),
            ExportColumn::make('last_check_in_at')
                ->label('Last Check In At'),
            ExportColumn::make('formatted_total_time_spent')
                ->label('Total Time Spent'),
            ExportColumn::make('checkedInByUser.name')
                ->label('Checked In By'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your current attendees export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
