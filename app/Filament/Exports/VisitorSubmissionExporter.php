<?php

namespace App\Filament\Exports;

use App\Models\VisitorSubmission;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class VisitorSubmissionExporter extends Exporter
{
    protected static ?string $model = VisitorSubmission::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('visitor.name')
                ->label('Visitor Name'),

            ExportColumn::make('visitor.email')
                ->label('Visitor Email'),

            ExportColumn::make('eventAnnouncement.title')
                ->label('Event Title'),

            ExportColumn::make('status')
                ->label('Status')
                ->state(fn(VisitorSubmission $record) => $record->status ? $record->status->value : null),

            // Badge information
            ExportColumn::make('has_badge')
                ->label('Has Badge')
                ->state(fn(VisitorSubmission $record) => $record->badge ? 'Yes' : 'No'),

            ExportColumn::make('badge_id')
                ->label('Badge ID')
                ->state(fn(VisitorSubmission $record) => $record->badge?->id),

            // Media attachments count
            // ExportColumn::make('attachments_count')
            //     ->label('Number of Attachments')
            //     ->state(fn(VisitorSubmission $record) => $record->getMedia('attachments')->count()),

            ExportColumn::make('created_at')
                ->label('Submitted At'),

            ExportColumn::make('updated_at')
                ->label('Last Updated'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your visitor submission export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
